import {Connection} from "../../library/connection";
import {RuleCategoryInterface, RuleInterface} from "../interface/rule.interface";
import {ClassInterface} from "../interface/class.interface";

export class RuleService implements ClassInterface {
    private rulesCategoryElement: Element;
    private rulesElement: Element;

    constructor(private connection: Connection) {
        this.rulesCategoryElement = document.querySelector('#rulesCategory > div > ul');
        this.rulesElement = document.querySelector('#rules');
    }

    public generate() {
        this.connection.get('rules').then((rules: RuleCategoryInterface[]) => {
            let lastRuleCategory = '';
            let ruleCategoryList = '';
            let ruleList = '';

            rules.forEach((element: RuleCategoryInterface, key: number) => {
                if (lastRuleCategory !== element.name) {
                    lastRuleCategory = element.name;
                    ruleCategoryList += `<li><a href="#${key}" class="nav-link">${element.name}</a></li>`;
                    ruleList += `
                        <h3 id="${key}" class="rule-category">${element.name}</h3>
                        <hr />
                    `;
                }
                ruleList += `<div class="rule-holder">`;
                element.rules.forEach((element: RuleInterface) => {
                    ruleList += `${element.description}`
                });
                ruleList += `</div>`;
            });

            this.rulesCategoryElement.innerHTML = ruleCategoryList;
            this.rulesElement.innerHTML = ruleList;
        });
    }
}
