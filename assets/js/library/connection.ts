import {Configuration} from "../app/config/configuration";

export class Connection {
    constructor(private configuration: Configuration) {}

    public get(uri: string, data: [] = null) {
        return fetch(this.configuration.apiURL + uri)
            .then(response => response.json())
    }

    public post(uri: string, data: [] = null) {
        return fetch(this.configuration.apiURL + uri, {
            method: 'POST'
        }).then(response => response.json())
    }
}
