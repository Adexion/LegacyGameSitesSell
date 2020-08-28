import {ArticleService} from "./article";
import {RuleService} from "./rule";
import {RecaptchaService} from "./recaptcha";

import {PathInterface} from "../interface/path.interface";

import {AvatarProvider} from "../../library/avatar.provider";
import store from "../../library/store";

export class Route {
    private paths: PathInterface[] = [
        {
            path: '/',
            class: new ArticleService(store.connection, new AvatarProvider(store.cookieService, store.connection))
        },
        {
            path: '/rule',
            class: new RuleService(store.connection)
        },
        {
            path: '/register',
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
