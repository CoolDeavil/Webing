import ICustomElement from "./iCustomElement";

export default interface IValidateResult {
    status: boolean;
    element: ICustomElement;
    message: string
}