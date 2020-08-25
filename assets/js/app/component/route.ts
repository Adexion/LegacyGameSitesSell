import {ArticleService} from "./article";
import {AvatarProvider} from "../../library/avatar.provider";
import {RuleService} from "./rule";
import {PathInterface} from "../interface/path.interface";
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
        }
    ];

    public runByPath() {
       const pathname = window.location.pathname;
        this.paths.forEach((element) => {
            if (pathname === element.path) {
                element.class.generate();
            }
        })
    }
}
