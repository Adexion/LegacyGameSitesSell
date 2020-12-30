import {RecaptchaService} from "./recaptcha";

import {PathInterface} from "../interface/path.interface";

import store from "../../library/store";
import {ItemShopService} from "./itemshop";

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
        },
        {
            path: '/itemshop',
            class: new ItemShopService(store.connection)
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
