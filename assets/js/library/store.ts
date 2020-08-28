import {Connection} from "./connection";
import {CookieService} from "./cookie.service";
import configuration from "../app/config/configuration";


class Store {
    public connection: Connection;
    public cookieService: CookieService;

    constructor() {
        this.cookieService = new CookieService();
        this.connection = new Connection()
    }
}

export default new Store();
