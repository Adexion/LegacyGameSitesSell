import {Route} from "./component/route";
import 'bootstrap';
import 'particles.js';

let route = new Route();
route.runByPath();

document.addEventListener('contextmenu', event => event.preventDefault());
