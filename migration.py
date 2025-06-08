import xml.etree.ElementTree as ET
import sqlite3
from pathlib import Path
import mimetypes
import base64

missing_audio_entries = []

# Parse the XML file
tree = ET.parse('master.xml')
root = tree.getroot()

# Connect to the SQLite database
db_path = "./data/data.db"
conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Clear existing data (in reverse dependency order)
cursor.execute("DELETE FROM lessons;")
cursor.execute("DELETE FROM sections;")
cursor.execute("DELETE FROM units;")
cursor.execute("DELETE FROM audios;")
conn.commit()

def handle_audio_file(filename, lesson_path=None):
    """Insert audio file into database if not already present, and return its ID."""
    if filename == ".mp3":
        return None

    cursor.execute("SELECT id FROM audios WHERE filename = ?", (filename,))
    row = cursor.fetchone()
    if row:
        return row[0]

    file_path = Path('audio') / filename
    if not file_path.exists():
        print(f"⚠️ Audio file not found: {filename}")
        if lesson_path:
            missing_audio_entries.append((lesson_path, filename))
        return None

    with open(file_path, 'rb') as f:
        data = f.read()

    mime_type, _ = mimetypes.guess_type(filename)
    if not mime_type:
        mime_type = 'audio/mpeg'

    cursor.execute(
        "INSERT INTO audios (filename, mime, data) VALUES (?, ?, ?)",
        (filename, mime_type, data)
    )
    return cursor.lastrowid


def convert_audio_section_to_html(tag, lesson_path=None):
    """Convert <dialog> or <vocab> tag to a table with audio."""
    heading = "Dialog" if tag.tag == "dialog" else "Vocabulary"
    table_rows = [f"<h2>{heading}</h2>", "<table><tbody>"]

    for line in tag.findall('line'):
        migmaq = line.findtext('migmaq', default='').strip()
        english = line.findtext('english', default='').strip()
        soundfile_name = line.findtext('soundfile', default='').strip() + ".mp3"
        audio_id = handle_audio_file(soundfile_name, lesson_path=lesson_path)

        audio_html = f"""
        <div class="se-component">
          <figure>
            <audio controls="true" origin-size="," src="/audio?id={audio_id}" data-file-name="{soundfile_name}" data-file-size="" style="" data-index="0"></audio>
          </figure>
        </div>""" if audio_id else "<!-- Audio not found -->"

        table_rows.append(f"<tr><td><div>{migmaq}<br></div></td><td rowspan='2'>{audio_html}</td></tr>")
        table_rows.append(f"<tr><td><div>{english}</div></td></tr>")

    table_rows.append("</tbody></table>")
    return '\n'.join(table_rows)


def convert_element_to_html(elem):
    """Recursively convert XML elements to HTML strings."""
    tag_map = {
        'note': 'p',
        'm': 'strong',
    }

    html_tag = tag_map.get(elem.tag, elem.tag)
    text = (elem.text or '')

    # Convert children recursively
    children_html = ''.join(convert_element_to_html(child) for child in elem)

    tail = elem.tail or ''
    return f"<{html_tag}>{text}{children_html}</{html_tag}>{tail}"
    
def convert_notes_to_html(notes):
    """Convert a list of <note> tags to HTML paragraphs, converting <m> to <strong>."""
    return '\n'.join(convert_element_to_html(note).strip() for note in notes)


# Process sections → units
for i, section in enumerate(root.findall('section')):
    section_title = section.find('title').text.strip()
    section_notes = section.findall('note')
    section_body = convert_notes_to_html(section_notes)

    # Insert into units table
    cursor.execute(
        "INSERT INTO units (title, body, status, position) VALUES (?, ?, 'draft', ?)",
        (section_title, section_body, i)
    )
    unit_id = cursor.lastrowid

    # Process units → sections
    for j, unit in enumerate(section.findall('unit')):
        unit_title = unit.find('title').text.strip()
        unit_notes = unit.findall('note')
        unit_body = convert_notes_to_html(unit_notes)

        # Insert into sections table
        cursor.execute(
            "INSERT INTO sections (unit_id, title, body, status, position) VALUES (?, ?, ?, 'draft', ?)",
            (unit_id, unit_title, unit_body, j)
        )
        section_id = cursor.lastrowid

        # Process lessons
        for k, lesson in enumerate(unit.findall('lesson')):
            lesson_title = lesson.find('title').text.strip()
            lesson_body_parts = []

            # Convert notes
            lesson_notes = lesson.findall('note')
            if lesson_notes:
                lesson_body_parts.append(convert_notes_to_html(lesson_notes))

            lesson_path = f"{unit_title} > {section_title} > {lesson_title}"
            print(f"Inserting {lesson_path}:")

            # Convert dialog/vocab if present
            for audio_tag in ['dialog', 'vocab']:
                audio_elem = lesson.find(audio_tag)
                if audio_elem is not None:
                    lesson_body_parts.append(convert_audio_section_to_html(audio_elem, lesson_path=lesson_path))


            lesson_body = '\n'.join(lesson_body_parts)

            # Insert into lessons table
            cursor.execute(
                "INSERT INTO lessons (section_id, title, body, status, position) VALUES (?, ?, ?, 'draft', ?)",
                (section_id, lesson_title, lesson_body, k)
            )

# Commit changes and close connection
conn.commit()
conn.close()

# Report missing audio files by lesson
if missing_audio_entries:
    print("\n--- Missing Audio Files by Lesson ---\n")
    for lesson_path, filename in missing_audio_entries:
        print(f"- {filename} is missing in: {lesson_path}")
else:
    print("\n✅ All audio files found.")

print("Import completed successfully.")
