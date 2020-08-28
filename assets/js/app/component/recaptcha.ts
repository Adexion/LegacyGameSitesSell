import {ClassInterface} from "../interface/class.interface";
import configuration from "../config/configuration";

export class RecaptchaService implements ClassInterface {
    generate() {
        document.querySelector('#recaptcha-component').innerHTML = `
            <div class="g-recaptcha"
                  data-sitekey="${configuration.recaptchaSiteKey}"
                  data-callback="onSubmit"
                  data-expired-callback="onExpired"
                  data-size="invisible">
            </div>
        `;

        let script = document.createElement('script');
        script.appendChild(document.createTextNode(`
                onSubmit = function(token) {
                    let recaptchaInput = document.querySelector('#reCaptcha');
                    recaptchaInput.value = token;
                };
                onExpired = function() {
                     grecaptcha.execute();
                };
                onloadCallback = function() {
                     grecaptcha.execute();
                };                
        `));

        let outsideScript = document.createElement("script");
        outsideScript.src = "https://www.google.com/recaptcha/api.js?onload=onloadCallback";
        outsideScript.setAttribute('async','');
        outsideScript.setAttribute('defer','');

        document.querySelector('#recaptcha-component').appendChild(script);
        document.querySelector('#recaptcha-component').appendChild(outsideScript);
    }
}
