import {Connection} from "../../library/connection";
import {ArticleInterface} from "../interface/article.interface";
import {AvatarProvider} from "../../library/avatar.provider";
import {ClassInterface} from "../interface/class.interface";

declare const window: any;

export class ArticleService implements ClassInterface {
    articleElement: Element;

    constructor(private connection: Connection, private avatarProvider: AvatarProvider) {
        window.avatarProvider = avatarProvider;
        window.articleElement = document.querySelector('#article-list');
    }

    public generate() {
        let articleHtml = '';
        this.connection.get('article').then((articles: ArticleInterface[]) => {
                articles.forEach(async function(article: ArticleInterface) {
                    let avatar = await window.avatarProvider.getAvatarByUsername(article.author.username);
                    window.articleElement.innerHTML += `
                        <div>
                            <p class="h4 font-weight-bold">${article.title}</h4>
                            <p class="h5">${article.subhead}</p>
                            <hr/>
                            <span>${article.shortText}</span>
                            <div class="pt-2">
                                <span class="float-left text-small"><a href="article/${article.id}">Czytaj więcej ...</a></span>
                                <span class="float-right text-small">~${article.author.username}&nbsp;<img class="avatar" src="${avatar}" alt="${article.author.username} avatar"/></span>
                            </div>
                        </div>
                    `;
                });
            }
        );
    }
}
