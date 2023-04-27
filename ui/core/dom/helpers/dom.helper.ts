import { AttributeNotFoundException } from '../exceptions/attribute-not-found.exception';
import { DatasetKeyNotFoundException } from '../exceptions/dataset-key-not-found.exception';
import { ElementNotFoundException } from '../exceptions/element-not-found.exception';
import { ElementListEmptyException } from '../exceptions/element-list-empty.exception';
import { isEmpty } from '../../utils/types/array/is-empty';
import { isNullable } from '../../utils/types/nulllable/is-nullable';
import { Nullable } from '../../utils/types/nulllable/nullable';

export class DomHelper {
    private readonly parser: DOMParser = new DOMParser();

    constructor(private documentToCheck: Document = document) {}

    querySelector<T extends Element>(query: string): T {
        const element: Nullable<T> = this.documentToCheck.querySelector<T>(query);

        if (isNullable(element)) {
            throw new ElementNotFoundException();
        }

        return element;
    }

    querySelectorAll<T extends Element>(query: string): NodeListOf<T> {
        const elements: NodeListOf<T> = this.documentToCheck.querySelectorAll<T>(query);

        if (isEmpty(Array.from(elements))) {
            throw new ElementListEmptyException();
        }

        return elements;
    }

    getData(element: HTMLElement, key: string): string {
        const data: Nullable<string> = element.dataset[key];

        if (isNullable(data)) {
            throw new DatasetKeyNotFoundException();
        }

        return data;
    }

    getAttribute(element: HTMLElement, key: string): string {
        const attribute: Nullable<string> = element.getAttribute(key);

        if (isNullable(attribute)) {
            throw new AttributeNotFoundException();
        }

        return attribute;
    }

    setAttribute(element: HTMLElement, key: string, value: string): void {
        element.setAttribute(key, value);
    }

    removeAttribute(element: HTMLElement, key: string): void {
        element.removeAttribute(key);
    }

    setValue(element: HTMLInputElement, value: string): void {
        element.value = value;
    }

    switchClass(element: HTMLElement, oldClass: string, newClass: string): void {
        element.classList.replace(oldClass, newClass);
    }

    replaceOuterHtml(oldElement: HTMLElement, newElement: HTMLElement): void {
        oldElement.outerHTML = newElement.outerHTML;
    }

    parseTextToHtml(text: string): Document {
        return this.parser.parseFromString(text, 'text/html');
    }
}

export default new DomHelper();
