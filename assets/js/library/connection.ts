import configuration from "../app/config/configuration";

export class Connection {
    public get(uri: string, data: [] = null) {
        return fetch(configuration.apiURL + uri)
            .then(response => response.json())
    }

    public post(uri: string, data: [] = null) {
        return fetch(configuration.apiURL + uri, {
            method: 'POST'
        }).then(response => response.json())
    }
}
