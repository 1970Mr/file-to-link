# Telegram Bot: File to Link

## Introduction

This repository contains a simple Telegram bot implemented in PHP using the Laravel framework. The bot is designed to handle file uploads and provide downloadable links. Below, you'll find instructions for setting up and running the bot.

## Requirements

- PHP >= 8.2
- Laravel >= 11.9
- MySQL
- Composer
- Telegram Bot Token

## Installation

1. **Clone the repository:**

   ```sh
   git clone https://github.com/1970Mr/file-to-link.git
   cd file-to-link
   ```

2. **Install dependencies:**

   ```sh
   composer install
   ```

3. **Set up environment variables:**

   Copy the `.env.example` to `.env` and fill in the necessary details, especially the `TELEGRAM_BOT_TOKEN`:

   ```sh
   cp .env.example .env
   ```

   Edit the `.env` file to include your Telegram bot token and other configurations:

   ```env
   TELEGRAM_BOT_TOKEN=your-telegram-bot-token
   APP_URL=https://your-app-url.com
   ```

4. **Generate application key:**

   ```sh
   php artisan key:generate
   ```

5. **Run database migrations:**

   ```sh
   php artisan migrate
   ```

## Setting up the Webhook

You need to set up a webhook for your Telegram bot to receive updates. There are two methods to set the webhook.

### Method 1: Using the Route

**Set the webhook using the route:**

   ```sh
   php artisan serve
   ```

   Open your browser and navigate to:

   ```
   http://localhost:8000/set-webhook/{your-telegram-bot-token}
   ```

   If everything is set up correctly, you should see a message: "Webhook is set successfully.".

### Method 2: Using an Artisan Command

**Set the webhook using an artisan command:**

   ```sh
   php artisan telegram:set-webhook
   ```

Your bot should now be up and running, ready to handle file uploads and provide downloadable links.

## Contributing

Feel free to submit issues or pull requests if you find any bugs or have suggestions for improvements.

## License

This project is open-source and available under the [MIT License](LICENSE).
