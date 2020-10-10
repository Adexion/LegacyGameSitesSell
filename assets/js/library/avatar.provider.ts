import {CookieService} from "./cookie.service";
import {Connection} from "./connection";

interface AvatarInterface {
    avatar: string;
}

export class AvatarProvider {
    constructor(private cookieService: CookieService, private connection: Connection) {
    }

    public async getAvatarByUsername(username: string): Promise<string> {
        let response: AvatarInterface = await this.connection.get('player/avatar?username=' + username);

        return response.avatar;
    }
}
