// class UserSession {
//   // --- Gestion des cookies ---
//   static setCookie(name, value, days) {
//     const expires = days
//       ? "; expires=" +
//         new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString()
//       : "";
//     document.cookie = `${name}=${encodeURIComponent(
//       value
//     )}${expires}; path=/; SameSite=Lax;`;
//   }

//   static getCookie(name) {
//     const cookies = document.cookie.split("; ");
//     for (const cookie of cookies) {
//       const [key, val] = cookie.split("=");
//       if (key === name) return decodeURIComponent(val);
//     }
//     return null;
//   }

//   static deleteCookie(name) {
//     document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
//   }

//   // --- Connexion Google ---
//   static onGoogleSignIn(googleUser) {
//     const profile = googleUser.getBasicProfile();
//     const userData = {
//       provider: "google",
//       id: profile.getId(),
//       name: profile.getName(),
//       email: profile.getEmail(),
//       imageUrl: profile.getImageUrl(),
//     };
//     UserSession.saveUser(userData);
//     console.log("Connecté via Google:", userData.name, userData.email);
//   }

//   // --- Sauvegarde des infos utilisateur dans les cookies ---
//   static saveUser(userData) {
//     UserSession.setCookie("user_provider", userData.provider, 7);
//     UserSession.setCookie("user_id", userData.id, 7);
//     UserSession.setCookie("user_name", userData.name, 7);
//     UserSession.setCookie("user_email", userData.email, 7);
//     if (userData.imageUrl)
//       UserSession.setCookie("user_image", userData.imageUrl, 7);
//   }

//   // --- Déconnexion ---
//   static logout() {
//     [
//       "user_provider",
//       "user_id",
//       "user_name",
//       "user_email",
//       "user_image",
//     ].forEach(UserSession.deleteCookie);
//     console.log("Déconnexion effectuée");
//   }

//   // --- Vérifier si utilisateur connecté ---
//   static isLoggedIn() {
//     return !!UserSession.getCookie("user_email");
//   }

//   // --- Obtenir les infos utilisateur ---
//   static getUser() {
//     if (!UserSession.isLoggedIn()) return null;
//     return {
//       provider: UserSession.getCookie("user_provider"),
//       id: UserSession.getCookie("user_id"),
//       name: UserSession.getCookie("user_name"),
//       email: UserSession.getCookie("user_email"),
//       imageUrl: UserSession.getCookie("user_image"),
//     };
//   }

//   // --- Affichage de la bannière RGPD avec Accepter / Refuser ---
//   static showConsentBanner(force = false) {
//     const consent = UserSession.getCookie("consent_cookies");
//     if (!consent || force) {
//       const oldBanner = document.getElementById("consent-banner");
//       if (oldBanner) oldBanner.remove();

//       const banner = document.createElement("div");
//       banner.id = "consent-banner";
//       banner.style.cssText = `
//         position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: #fff;
//         padding: 15px; text-align: center; z-index: 10000; font-family: sans-serif;
//       `;
//       banner.innerHTML = `
//       <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;">
//       <span style="flex: 1; min-width: 200px;">
//             Nous utilisons des cookies pour améliorer votre expérience et activer certaines fonctionnalités, comme la connexion via Google.
//             Vous pouvez accepter ou refuser leur utilisation.
//       </span>
//       <div style="flex-shrink: 0; display: flex; gap: 10px; margin-top: 5px;">
//             <button id="acceptCookies" style="padding: 6px 12px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">
//             Accepter
//             </button>
//             <button id="declineCookies" style="padding: 6px 12px; cursor: pointer; background-color: #f44336; color: white; border: none; border-radius: 4px;">
//             Refuser
//             </button>
//       </div>
//       </div>
//       `;
//       document.body.appendChild(banner);

//       document.getElementById("acceptCookies").addEventListener("click", () => {
//         UserSession.setCookie("consent_cookies", "yes", 365);
//         banner.remove();
//         UserSession.handleGoogleSignInButton();
//       });

//       document
//         .getElementById("declineCookies")
//         .addEventListener("click", () => {
//           UserSession.setCookie("consent_cookies", "no", 365);
//           banner.remove();
//           UserSession.handleGoogleSignInButton();
//         });
//     }
//   }

//   // --- Gestion du bouton Google Sign-In ---
//   static handleGoogleSignInButton() {
//     const googleBtn = document.querySelector(".g_id_signin");
//     if (!googleBtn) return;

//     if (UserSession.getCookie("consent_cookies") === "yes") {
//       googleBtn.style.display = "block";
//     } else {
//       googleBtn.style.display = "none";
//     }
//   }

//   // --- Afficher la bannière de consentement à la demande ---
//   static openConsentSettings() {
//     UserSession.showConsentBanner(true);
//   }
// }

// // --- Initialisation au chargement du DOM ---
// document.addEventListener("DOMContentLoaded", () => {
//   UserSession.showConsentBanner();
//   UserSession.handleGoogleSignInButton();

//   // Lien pour modifier les paramètres cookies
//   const cookieSettingsLink = document.getElementById("cookie-settings");
//   if (cookieSettingsLink) {
//     cookieSettingsLink.addEventListener("click", (e) => {
//       e.preventDefault();
//       UserSession.openConsentSettings();
//     });
//   }
// });
class UserSession {
  // --- Gestion des cookies ---
  static setCookie(name, value, days) {
    const expires = days
      ? "; expires=" +
        new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString()
      : "";
    document.cookie = `${name}=${encodeURIComponent(
      value
    )}${expires}; path=/; SameSite=Lax;`;
  }

  static getCookie(name) {
    const cookies = document.cookie.split("; ");
    for (const cookie of cookies) {
      const [key, val] = cookie.split("=");
      if (key === name) return decodeURIComponent(val);
    }
    return null;
  }

  static deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
  }

  // --- Connexion Google ---
  static onGoogleSignIn(googleUser) {
    const profile = googleUser.getBasicProfile();
    const userData = {
      provider: "google",
      id: profile.getId(),
      name: profile.getName(),
      email: profile.getEmail(),
      imageUrl: profile.getImageUrl(),
    };
    UserSession.saveUser(userData);
    console.log("Connecté via Google:", userData.name, userData.email);
  }

  // --- Sauvegarde des infos utilisateur dans les cookies ---
  static saveUser(userData) {
    UserSession.setCookie("user_provider", userData.provider, 7);
    UserSession.setCookie("user_id", userData.id, 7);
    UserSession.setCookie("user_name", userData.name, 7);
    UserSession.setCookie("user_email", userData.email, 7);
    if (userData.imageUrl)
      UserSession.setCookie("user_image", userData.imageUrl, 7);
  }

  // --- Déconnexion ---
  static logout() {
    [
      "user_provider",
      "user_id",
      "user_name",
      "user_email",
      "user_image",
    ].forEach(UserSession.deleteCookie);
    console.log("Déconnexion effectuée");
  }

  // --- Vérifier si utilisateur connecté ---
  static isLoggedIn() {
    return !!UserSession.getCookie("user_email");
  }

  // --- Obtenir les infos utilisateur ---
  static getUser() {
    if (!UserSession.isLoggedIn()) return null;
    return {
      provider: UserSession.getCookie("user_provider"),
      id: UserSession.getCookie("user_id"),
      name: UserSession.getCookie("user_name"),
      email: UserSession.getCookie("user_email"),
      imageUrl: UserSession.getCookie("user_image"),
    };
  }

  // --- Affichage de la bannière RGPD avec Accepter / Refuser ---
  static showConsentBanner(force = false) {
    const consent = UserSession.getCookie("consent_cookies");
    if (!consent || force) {
      const oldBanner = document.getElementById("consent-banner");
      if (oldBanner) oldBanner.remove();

      const banner = document.createElement("div");
      banner.id = "consent-banner";
      banner.style.cssText = `
        position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: #fff;
        padding: 15px; text-align: center; z-index: 10000; font-family: sans-serif;
      `;
      banner.innerHTML = `
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;">
          <span style="flex: 1; min-width: 200px;">
            Nous utilisons des cookies pour améliorer votre expérience et activer certaines fonctionnalités, comme la connexion via Google.
            Vous pouvez accepter ou refuser leur utilisation.
          </span>
          <div style="flex-shrink: 0; display: flex; gap: 10px; margin-top: 5px;">
            <button id="acceptCookies" style="padding: 6px 12px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">
              Accepter
            </button>
            <button id="declineCookies" style="padding: 6px 12px; cursor: pointer; background-color: #f44336; color: white; border: none; border-radius: 4px;">
              Refuser
            </button>
          </div>
        </div>
      `;
      document.body.appendChild(banner);

      document.getElementById("acceptCookies").addEventListener("click", () => {
        UserSession.setCookie("consent_cookies", "yes", 365);
        banner.remove();
        UserSession.handleGoogleSignInButton();
      });

      document
        .getElementById("declineCookies")
        .addEventListener("click", () => {
          UserSession.setCookie("consent_cookies", "no", 365);
          banner.remove();
          UserSession.handleGoogleSignInButton();
        });
    }
  }

  // --- Gestion du bouton Google Sign-In ---
  static handleGoogleSignInButton() {
    const googleBtn = document.querySelector(".g_id_signin");
    if (!googleBtn) return;

    if (UserSession.getCookie("consent_cookies") === "yes") {
      googleBtn.style.display = "block";
    } else {
      googleBtn.style.display = "none";
    }
  }

  // --- Afficher la bannière de consentement à la demande ---
  static openConsentSettings() {
    UserSession.showConsentBanner(true);
  }
}

// --- Initialisation au chargement du DOM ---
document.addEventListener("DOMContentLoaded", () => {
  UserSession.showConsentBanner();
  UserSession.handleGoogleSignInButton();

  // Lien pour modifier les paramètres cookies
  const cookieSettingsLink = document.getElementById("cookie-settings");
  if (cookieSettingsLink) {
    cookieSettingsLink.addEventListener("click", (e) => {
      e.preventDefault();
      UserSession.openConsentSettings();
    });
  }
});
