export class ErrorContainer {
    html: string;
    errorMessageElementClass: string;
    errorContainerClass: string

    constructor(html: string, errorMessageElementClass: string, errorContainerClass: string) {
        this.html = html;
        this.errorMessageElementClass = errorMessageElementClass;
        this.errorContainerClass = errorContainerClass;
    }
}
