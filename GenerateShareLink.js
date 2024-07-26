class GenerateShareLink {
    /**
     * Generate a link to send a message via WhatsApp.
     *
     * @param {string} number - The WhatsApp number, including the country code.
     * @param {string} message - The message to send.
     * @returns {string} The generated WhatsApp link.
     */
    static toWhatsApp(number, message) {
        return `https://wa.me/${number}?text=${encodeURIComponent(message)}`;
    }

    /**
     * Generate a mailto link for sending an email.
     *
     * @param {string} email - The recipient email address.
     * @param {string} subject - The subject of the email.
     * @param {string} message - The body of the email.
     * @returns {string} The generated mailto link.
     */
    static toEmail(email, subject, message) {
        return `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
    }

    /**
     * Generate a link to share a URL on Facebook.
     *
     * @param {string} url - The URL to share.
     * @returns {string} The generated Facebook share link.
     */
    static toFacebook(url) {
        return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    }

    /**
     * Generate a link to share a URL on Twitter with a custom text.
     *
     * @param {string} url - The URL to share.
     * @param {string} text - The text to include in the tweet.
     * @returns {string} The generated Twitter share link.
     */
    static toTwitter(url, text) {
        return `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
    }

    /**
     * Generate a link to share a URL on LinkedIn with a title and summary.
     *
     * @param {string} url - The URL to share.
     * @param {string} title - The title of the shared content.
     * @param {string} summary - A summary of the shared content.
     * @returns {string} The generated LinkedIn share link.
     */
    static toLinkedIn(url, title, summary) {
        return `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(url)}&title=${encodeURIComponent(title)}&summary=${encodeURIComponent(summary)}`;
    }

    /**
     * Generate a link to share a URL on Pinterest with a media and description.
     *
     * @param {string} url - The URL to share.
     * @param {string} media - The media URL (image, video, etc.) to attach.
     * @param {string} description - The description of the media.
     * @returns {string} The generated Pinterest share link.
     */
    static toPinterest(url, media, description) {
        return `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(url)}&media=${encodeURIComponent(media)}&description=${encodeURIComponent(description)}`;
    }

    /**
     * Generate a link to share a URL on Telegram with a custom text.
     *
     * @param {string} url - The URL to share.
     * @param {string} text - The text to include with the shared URL.
     * @returns {string} The generated Telegram share link.
     */
    static toTelegram(url, text) {
        return `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
    }

    /**
     * Generate a link to send an SMS with a message.
     *
     * @param {string} number - The phone number to send the SMS to.
     * @param {string} message - The message to send.
     * @returns {string} The generated SMS link.
     */
    static toSMS(number, message) {
        return `sms:${number}?body=${encodeURIComponent(message)}`;
    }

    /**
     * Generate a link to share a URL on Line with a custom text.
     *
     * @param {string} url - The URL to share.
     * @param {string} text - The text to include with the shared URL.
     * @returns {string} The generated Line share link.
     */
    static toLine(url, text) {
        return `https://lineit.line.me/share/ui?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
    }

    /**
     * Generate a link to send a message via Viber.
     *
     * @param {string} number - The phone number to send the message to.
     * @param {string} text - The message to send.
     * @returns {string} The generated Viber link.
     */
    static toViber(number, text) {
        return `viber://forward?text=${encodeURIComponent(text)}&phone=${number}`;
    }
}

// Contoh penggunaan:
console.log(GenerateShareLink.toWhatsApp('1234567890', 'Hello! Check this out.'));
console.log(GenerateShareLink.toEmail('example@example.com', 'Subject', 'Message body'));
console.log(GenerateShareLink.toFacebook('https://example.com'));
console.log(GenerateShareLink.toTwitter('https://example.com', 'Check this out!'));
console.log(GenerateShareLink.toLinkedIn('https://example.com', 'Title', 'Summary'));
console.log(GenerateShareLink.toPinterest('https://example.com', 'https://example.com/image.jpg', 'Description'));
console.log(GenerateShareLink.toTelegram('https://example.com', 'Check this out!'));
console.log(GenerateShareLink.toSMS('1234567890', 'Hello! Check this out.'));
console.log(GenerateShareLink.toLine('https://example.com', 'Check this out!'));
console.log(GenerateShareLink.toViber('1234567890', 'Hello! Check this out.'));
