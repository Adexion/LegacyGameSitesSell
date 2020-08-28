import {Connection} from "../../library/connection";
import {ArticleInterface} from "../interface/article.interface";
import {AvatarProvider} from "../../library/avatar.provider";
import {ClassInterface} from "../interface/class.interface";

export class ArticleService implements ClassInterface {
    articleElement: Element;

    constructor(private connection: Connection, private avatarProvider: AvatarProvider) {
        this.articleElement = document.querySelector('#article-list');
    }

    public generate() {
        this.connection.get('article').then((articles: ArticleInterface[]) => articles.forEach((article: ArticleInterface) => {
            this.avatarProvider.getAvatarByUsername(article.author.username).then(avatar => {
                this.articleElement.innerHTML = `
                    <div>
                        <h4 class="font-weight-bold">${article.title}</h4>
                        <h5>${article.subhead}</h5>
                        <hr/>
                        <span>${article.shortText}</span>
                        <div class="pt-2">
                            <span class="float-left text-small"><a href="article/${article.id}">Czytaj więcej ...</a></span>
                            <span class="float-right text-small">~${article.author.username}&nbsp;<img class="avatar" src="${avatar}" alt="${article.author.username} avatar"/></span>
                        </div>
                    </div>
                `;
            });
        }));
    }
}
