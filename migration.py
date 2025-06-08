import xml.etree.ElementTree as ET
import sqlite3
from pathlib import Path

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
conn.commit()

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
            lesson_notes = lesson.findall('note')
            lesson_body = convert_notes_to_html(lesson_notes)

            # Insert into lessons table
            cursor.execute(
                "INSERT INTO lessons (section_id, title, body, status, position) VALUES (?, ?, ?, 'draft', ?)",
                (section_id, lesson_title, lesson_body, k)
            )

# Commit changes and close connection
conn.commit()
conn.close()

print("Import completed successfully.")
