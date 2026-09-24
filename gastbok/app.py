from flask import Flask, request, render_template
import json
import os
from datetime import datetime

app = Flask(__name__)

JSON_FILE = 'data.json'


# Läser in alla inlägg från JSON-filen
def load_posts():
    if not os.path.exists(JSON_FILE):
        return []

    try:
        with open(JSON_FILE, encoding='utf-8') as f:
            return json.load(f)

    except Exception:
        return []


# Startsidan
@app.route('/')
def index():

    posts = load_posts()

    return render_template(
        'index.html',
        posts=posts
    )


# När formuläret skickas
@app.route('/write-json', methods=['POST'])
def write_json():

    # Läs in tidigare inlägg
    posts = load_posts()

    # Hämta information från formuläret
    name = request.form.get('namn', '').strip()
    message = request.form.get('meddelande', '').strip()

    # Kontrollera att fälten inte är tomma
    if name and message:

        # Skapa nytt inlägg
        new_post = {
            'namn': name,
            'meddelande': message,
            'tid': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        }

        # Lägg till det nya inlägget
        posts.append(new_post)

        # Spara alla inlägg i JSON-filen
        with open(JSON_FILE, 'w', encoding='utf-8') as f:
            json.dump(
                posts,
                f,
                indent=4,
                ensure_ascii=False
            )

    # Visa sidan igen
    return render_template(
        'index.html',
        posts=posts
    )


app.run(debug=True)