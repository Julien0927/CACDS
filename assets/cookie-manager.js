// cookie-manager.js
class CookieManager {
    constructor() {
        const savedConsent = this.getCookie('cookieConsent');
        if (!savedConsent) {
            this.showBanner();
        }
    }

    showBanner() {
        const banner = document.createElement('div');
        banner.className = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-banner-content">
                <p>🍪 Ce site utilise des cookies pour améliorer votre expérience.</p>
                <div class="cookie-banner-buttons">
                    <button class="accept">Accepter</button>
                    <button class="decline">Refuser</button>
                </div>
            </div>
        `;

        document.body.appendChild(banner);

        // Animation d'entrée
        setTimeout(() => banner.classList.add('show'), 100);

        // Gestionnaires d'événements
        banner.querySelector('.accept').addEventListener('click', () => {
            this.setCookie('cookieConsent', true, 365);
            this.hideBanner(banner);
        });

        banner.querySelector('.decline').addEventListener('click', () => {
            this.setCookie('cookieConsent', false, 365);
            this.hideBanner(banner);
        });
    }

    hideBanner(banner) {
        banner.classList.remove('show');
        setTimeout(() => banner.remove(), 300);
    }

    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/;SameSite=Strict`;
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
}

// Styles
const styles = `
    .cookie-banner {
        position: fixed;
        bottom: -100px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(33, 33, 33, 0.98);
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        margin: 20px;
    }

    .cookie-banner.show {
        bottom: 0;
    }

    .cookie-banner-content {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 14px;
    }

    .cookie-banner-buttons {
        display: flex;
        gap: 10px;
    }

    .cookie-banner button {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s;
    }

    .cookie-banner button:hover {
        transform: scale(1.05);
    }

    .cookie-banner .accept {
        background: #4db769;
        color: white;
    }

    .cookie-banner .decline {
        background: transparent;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    @media (max-width: 600px) {
        .cookie-banner-content {
            flex-direction: column;
            text-align: center;
        }
        .cookie-banner {
            width: calc(100% - 40px);
        }
    }
`;

// Ajout des styles
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

// Initialisation
document.addEventListener('DOMContentLoaded', () => new CookieManager());