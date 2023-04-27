import { DomHelper } from '../core/dom/helpers/dom.helper'
import { fromEvent } from 'rxjs';
import { ContentType } from '../core/http/content-type';
import { BillingAddressField } from './model/billing-address-field';
import { BillingAddressFields } from './model/billing-address-fields';
import { ErrorContainer } from './model/error-container';
import { NipViewElementId } from './model/nip-view-element-id';

export class NipLoader {
    defaultDomHelper: DomHelper = new DomHelper();
    wantInvoiceSwitchId: string = '#sylius_checkout_address_wantInvoice';
    nipContainer: string = '#js-nip-container';
    wantDifferentAddressForBillingId: string = '#sylius_checkout_address_differentBillingAddress';
    getNipDataId: string = '#js-get-data-to-billing-address-btn';
    nipInputId: string = '#sylius_checkout_address_nip';
    billingAddressFormFieldIdPrefix: string = '#sylius_checkout_address_billingAddress_';
    billingAddressFields: BillingAddressField[];
    errorContainer: ErrorContainer;

    constructor(params: NipViewElementId = {}, billingAddressField: BillingAddressField[] = [], errorContainer: ErrorContainer | null = null) {
        Object.assign(this, params);

        if (null == errorContainer) {
            this.errorContainer = this.getDefaultErrorContainer();
        } else {
            this.errorContainer = errorContainer;
        }
        Object.assign(this.errorContainer, errorContainer)
        if (billingAddressField.length == 0) {
            this.billingAddressFields = this.loadDefaultBillingAddressField();
        } else {
            this.billingAddressFields = billingAddressField;
        }
    }

    public initialize(): void {
        document.addEventListener("DOMContentLoaded", () => {
            this.listenWantInvoiceCheckboxClick();
            this.listenNipDataClick();
        });
    }

    listenWantInvoiceCheckboxClick(): void {
        try {
            const wantInvoiceCheckbox: HTMLInputElement = this.defaultDomHelper.querySelector(this.wantInvoiceSwitchId);
            fromEvent(wantInvoiceCheckbox, 'change').subscribe((event: Event) => {
                const isChecked: boolean = (<HTMLInputElement>event.target).checked;

                const nipContainer: HTMLElement = this.defaultDomHelper.querySelector(this.nipContainer);
                if (isChecked) {
                    nipContainer.style.display = 'block';
                    this.manageDifferentAddressForBillingVisibility()

                } else {
                    nipContainer.style.display = 'none';
                }
            });
        } catch (e) {
            return;
        }
    }

    manageDifferentAddressForBillingVisibility(): void {
        try {
            const differentAddressForBilling: HTMLInputElement = this.defaultDomHelper.querySelector(this.wantDifferentAddressForBillingId);
            const isChecked: boolean = differentAddressForBilling.checked;
            if (!isChecked) {
                differentAddressForBilling.click();
            }
        } catch (e) {
            return;
        }
    }

    isNipDataButton(): boolean {
        try {
            this.defaultDomHelper.querySelector(this.getNipDataId);

            return true;
        } catch (e) {
            return false;
        }
    }

    listenNipDataClick(): void {
        if (!this.isNipDataButton()) {
            return;
        }

        const nipDataBtn: HTMLElement = this.defaultDomHelper.querySelector(this.getNipDataId);
        fromEvent(nipDataBtn, 'click').subscribe((event: Event) => {
            event.preventDefault();
            const nipInput: HTMLInputElement = this.defaultDomHelper.querySelector(this.nipInputId);
            let nip: string = nipInput.value;
            var postObj = {
                lemisoft_sylius_invoice_request_gus_get_data:
                    {
                        nip: nip,
                        _token: nipDataBtn.dataset['token'],
                    },
            }
            const data: string = JSON.stringify(postObj);
            const url: string | undefined = nipDataBtn.dataset['url'];
            if (undefined == url) {
                throw new Error('Brak akcji do pobrania danych');
            }
            this.sendPostRequest(url, data);
        });
    }

    sendPostRequest(url: string, data: string): void {
        const nipLoader: this = this;
        const xhr: XMLHttpRequest = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', ContentType.APPLICATION_JSON + '; charset=UTF-8');
        xhr.send(data);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const obj = JSON.parse(this.response);
                nipLoader.setDataToBillingAddress(obj);
            } else if (xhr.status === 400) {
                const obj = JSON.parse(this.response);
                nipLoader.clearError();
                obj.errors.children.nip.errors.forEach((message: string) => {
                    let errorContainer: HTMLElement = nipLoader.prepareErrorMessage(message);
                    nipLoader.showError(errorContainer);
                });
            }
        }
    }

    loadDefaultBillingAddressField(): BillingAddressField[] {
        const keys: string[] = Object.keys(BillingAddressFields);
        const values: string[] = Object.values(BillingAddressFields);
        let result = [];
        for (const inputFieldName of keys) {
            let index: number = keys.indexOf(inputFieldName);
            let objAttributeName = values[index];

            result.push(new BillingAddressField(inputFieldName, objAttributeName));
        }

        return result;
    }

    setDataToBillingAddress(obj: any): void {
        this.billingAddressFields.forEach((model: BillingAddressField): void => {
            this.setDataToInput(model, obj);
        });
    }

    setDataToInput(model: BillingAddressField, obj: any): void {
        try {
            const input: HTMLInputElement = this.defaultDomHelper.querySelector(this.billingAddressFormFieldIdPrefix + model.formName);
            this.defaultDomHelper.setValue(input, obj[model.objectName]);
        } catch (e) {
            return;
        }
    }

    prepareErrorMessage(message: string) {
        const errorDocument: Document = this.defaultDomHelper.parseTextToHtml(this.errorContainer.html);
        const errorElement: HTMLElement = new DomHelper(errorDocument).querySelector(this.errorContainer.errorContainerClass);
        const errorMessageContainer: HTMLElement = new DomHelper(errorDocument).querySelector(this.errorContainer.errorMessageElementClass);
        const textNode = document.createTextNode(message);
        errorMessageContainer.appendChild(textNode);

        return errorElement;
    }

    getDefaultErrorContainer() {
        return new ErrorContainer(
            '<div class="ui red pointing label sylius-validation-error"></div>',
            '.sylius-validation-error',
            '.sylius-validation-error',
        );
    }

    clearError() {
        const nipInputParent: Element = this.defaultDomHelper.querySelector(this.nipContainer);
        const errorDiv: Element | null = nipInputParent.firstElementChild;
        if (null !== errorDiv) {
            const errors: HTMLCollectionOf<Element> = errorDiv.getElementsByClassName(this.errorContainer.errorContainerClass.substring(1));
            Array.from(errors).forEach(
                function (element: Element) {
                    element.remove();
                },
            );
        }
    }

    showError(errorContainer: HTMLElement) {
        const nipInputParent: Element = this.defaultDomHelper.querySelector(this.nipContainer);
        const errorDiv: Element | null = nipInputParent.firstElementChild;
        if (null !== errorDiv) {
            errorDiv.appendChild(errorContainer);
        }
    }
}
