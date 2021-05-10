import {RecaptchaService} from "./recaptcha";
import {PathInterface} from "../interface/path.interface";

export class Route {
    private paths: PathInterface[] = [
        {
            path: '/register',
            class: new RecaptchaService()
        },
        {
            path: '/contact',
            class: new RecaptchaService()
        },
        {
            path: '/reset',
            class: new RecaptchaService()
        }
    ];

    public runByPath() {
       const pathname = window.location.pathname;
        this.paths.forEach((element) => {
            if (pathname === element.path) {
                element.class.generate();
            }
        });
    }
}
