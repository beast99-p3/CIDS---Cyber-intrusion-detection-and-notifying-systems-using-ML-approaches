# Intrusion Detection System (IDS) with Real-Time Alerts

This project implements a machine learning–based Intrusion Detection System (IDS) that detects and classifies network intrusions, and provides real-time alerts via email and Telegram, along with a web-based dashboard for visualizing attack statistics.

The core model is trained on the KDD Cup–style IDS dataset and can classify more than 10 types of cyberattacks, achieving 90%+ accuracy in the provided notebooks.

---

## Project Structure

Key files and folders (relative to this README):

- `final project dt1.ipynb` – End-to-end IDS notebook (data prep, training, evaluation, alerting, and web-output generation).
- `final project dt2.ipynb` – Alternative / variant pipeline for training and evaluation with the same alerting and web-output logic.
- `cyberattack/` – PHP-based web interface that displays attack statistics and a bar-chart image.
  - `cyberattack/data.txt` – Text file where the notebooks write attack category counts for the web UI (generated at runtime).
  - `cyberattack/index.php`, `attacks.php`, `bindex.php`, etc. – Web pages that render current attack statistics.
  - `cyberattack/index.css` – Styles for the dashboard.
  - `cyberattack/dataset/` – Dataset-related files (e.g., `kddcup.names`, `training_attack_types`).

Additional folders such as `New folder/xampp` and `New folder/dashboard` are environment-specific (XAMPP / Bitnami tooling) and are not required to understand or run the IDS logic itself.

---

## Features

- **Machine Learning–Based IDS**
  - Uses supervised learning (e.g., Random Forest, Decision Tree, Naive Bayes) to classify network flows as normal or one of several attack types.
  - Trained on a KDD Cup–style intrusion detection dataset.
  - Achieves over 90% accuracy in the Random Forest configuration shown in the notebooks.

- **Multi-Class Attack Classification**
  - Detects and groups attacks into categories such as `dos`, `probe`, `r2l`, `u2r`, etc.
  - Computes the number of suspected packets per attack category.

- **Web-Based Visualization**
  - Writes aggregated attack counts to `cyberattack/data.txt`.
  - Generates a bar chart of attack counts (`plot1.png`) for display in the PHP web UI.

- **Real-Time Alerting**
  - Sends email notifications with the attack statistics summary.
  - Sends Telegram messages to configured chat IDs when new attacks are detected.

---

## Configuration

All sensitive values have been **sanitized** and replaced with placeholders so the project is safe to publish publicly. To run the system end-to-end, you must configure:

### 1. Web Root Path (`PATH_TO_WEB_ROOT`)

In the final notebooks, file paths that write data for the web UI use a placeholder such as:

```python
f = open("PATH_TO_WEB_ROOT/cyberattack/data.txt", "w")
plt.savefig('PATH_TO_WEB_ROOT/cyberattack/plot1.png', bbox_inches='tight')
```

Replace `PATH_TO_WEB_ROOT` with the document root of your web server, for example:

- On XAMPP (Windows): `C:/xampp/htdocs`
- On a Linux Apache setup: `/var/www/html`

So the resulting path might look like:

```python
f = open("C:/xampp/htdocs/cyberattack/data.txt", "w")
plt.savefig('C:/xampp/htdocs/cyberattack/plot1.png', bbox_inches='tight')
```

Make sure the `cyberattack` folder is copied into your web root so that the PHP pages and data files line up.

### 2. Email (SMTP) Credentials

The notebooks define a `send_mail` helper using placeholders, for example:

```python
sender_address = 'SENDER_EMAIL@example.com'
sender_pass = 'SENDER_EMAIL_PASSWORD'
receiver_address = ['RECIPIENT_1@example.com', 'RECIPIENT_2@example.com']
```

Update these values:

- `SENDER_EMAIL@example.com` – The email address your SMTP provider allows you to send from.
- `SENDER_EMAIL_PASSWORD` – An app-specific password or SMTP password (do **not** use or commit your real password in a public repo).
- `RECIPIENT_*.example.com` – The administrator email addresses that should receive alerts.

The SMTP logic assumes Gmail-like settings:

```python
session = smtplib.SMTP('smtp.gmail.com', 587)
session.starttls()
session.login(sender_address, sender_pass)
```

If you use another provider (e.g., Outlook, corporate SMTP), adjust the host, port, and security as required.

### 3. Telegram Bot Token and Chat IDs

Telegram alerts are sent via a small helper class, e.g.:

```python
class BotHandler:
    def __init__(self, token):
        self.token = token
        self.api_url = "https://api.telegram.org/bot{}/".format(token)

    def send_message(self, chat_id, text):
        params = {'chat_id': chat_id, 'text': text, 'parse_mode': 'HTML'}
        method = 'sendMessage'
        resp = requests.post(self.api_url + method, params)
        return resp


def send_telegram():
    token = 'YOUR_TELEGRAM_BOT_TOKEN'
    hacker_bot = BotHandler(token)

    sendid = ['CHAT_ID_1']
    for ids in sendid:
        hacker_bot.send_message(ids, 'Cyber attack has been detected, please check your mail')
```

Configure:

- `YOUR_TELEGRAM_BOT_TOKEN` – Token for a bot you create with [@BotFather](https://t.me/BotFather).
- `CHAT_ID_1` (and any additional IDs) – Chat IDs of users or groups to notify.

You can obtain your chat ID by messaging your bot and querying the Telegram API (or by using any common "get chat id" helper bot).

### 4. Dataset Paths

Some early cells in the notebooks reference dataset paths like:

```python
path = "C:/Users/.../dataset/idsml1.csv"
```

Update `path` to point to the location where you store the dataset on your machine. The dataset description files (`kddcup.names`, `training_attack_types`, etc.) are located under `cyberattack/dataset/` in this project; you can keep them there or move them to a directory of your choice and update the notebook paths accordingly.

---

## How to Run

### 1. Set Up the Environment

1. Install Python 3 and Jupyter (or VS Code with the Jupyter extension).
2. Install required Python libraries (example):

   ```bash
   pip install pandas numpy matplotlib seaborn scikit-learn requests
   ```

3. Set up a web server (e.g., XAMPP, Apache, or similar) and copy the `cyberattack` folder into its document root.

### 2. Configure Placeholders

Before running the notebooks:

1. Open `final project dt1.ipynb` and/or `final project dt2.ipynb`.
2. Search for and update the following placeholders:
   - `PATH_TO_WEB_ROOT`
   - `SENDER_EMAIL@example.com`
   - `SENDER_EMAIL_PASSWORD`
   - `RECIPIENT_1@example.com`, `RECIPIENT_2@example.com`
   - `YOUR_TELEGRAM_BOT_TOKEN`
   - `CHAT_ID_1` (and any other chat IDs)
3. Adjust dataset `path` variables to match your file locations.

### 3. Train and Evaluate the Model

In `final project dt2.ipynb` (or `final project dt1.ipynb`):

1. Run the cells in order to:
   - Load and preprocess the dataset.
   - Train the models (Naive Bayes, Decision Tree, Random Forest).
   - Evaluate accuracy and timing.
2. The Random Forest configuration is typically used as the primary IDS model.

### 4. Generate Web Outputs and Alerts

When you run the final detection / alerting cells:

- The notebook will:
  - Predict attack types on the test set.
  - Aggregate counts per attack category.
  - Write the statistics to `cyberattack/data.txt` under your web root.
  - Generate and save `plot1.png` in `cyberattack/`.
  - Send email alerts with the summary.
  - Send Telegram notifications to the configured chat IDs.

Open your web browser and navigate to your web server (for example, `http://localhost/cyberattack/`) to see the dashboard and charts.
