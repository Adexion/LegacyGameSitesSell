import {CookieService} from "./cookie.service";

class Store {
    public cookieService: CookieService;

    constructor() {
        this.cookieService = new CookieService();
    }
}

export default new Store();
