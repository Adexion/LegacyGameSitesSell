import {Connection} from "./connection";
import {Configuration} from "../app/config/configuration";
import {CookieService} from "./cookie.service";


class Store {
    public connection: Connection;
    public cookieService: CookieService;

    constructor() {
        this.cookieService = new CookieService();
        this.connection = new Connection(new Configuration())
    }
}

export default new Store();
