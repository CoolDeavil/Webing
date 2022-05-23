import {IFormSecure} from "../Interfaces/iFormSecure";
import IFormField from "../Interfaces/iFormField";
import ICustomElement from "../Interfaces/iCustomElement";
import IValidateResult from "../Interfaces/iValidateResult";
import IParsedRule from "../Interfaces/iParsedRule";
import FlashMessage from "./flashMessage";

export default class FormRules {

    private form: HTMLFormElement
    private readonly watchFields: ICustomElement[];
    private watchRules: IFormField[]
    private asyncTotalCount: number;
    private asyncCallBacks: any[]
    private asyncCount: number;

    private flash: FlashMessage;
    private readonly options: IFormSecure;

    constructor(options: IFormSecure ) {
        if(options.form === '' || !document.querySelector(`#${options.form}`)){
            throw 'Define the target Form to validate';
        }
        this.form  = document.querySelector(`#${options.form}`);
        this.form.autocomplete = "off";
        this.form.noValidate = true;
        this.watchRules = options.fields;
        this.watchFields = [];
        this.asyncCallBacks=[];
        this.asyncTotalCount=0;
        this.asyncCount=0;
        this.options = options
        this.flash = new FlashMessage()
        this.bootstrap();
    }

    bootstrap() : void {
        this.watchRules.forEach((rule, index)=>{
            let nodeList: any;
            switch (this.form.elements[rule.name].type){
                case 'select-one':
                case 'select-multiple':
                    nodeList = this.form.querySelectorAll(`select[name="${rule.name}"]`)
                    break;
                default:
                    nodeList = this.form.querySelectorAll(`input[name="${rule.name}"]`)
                    break
            }
            nodeList.forEach(node=>{
                this.watchFields.push((<ICustomElement>node));
                this.watchFields[this.watchFields.length-1].isValidate = false;
                this.watchFields[this.watchFields.length-1].addEventListener('change',this.handleFieldChange.bind(this), false);
                this.watchFields[this.watchFields.length-1].addEventListener('blur',this.handleFieldChange.bind(this), false);
                this.watchFields[this.watchFields.length-1].addEventListener('keyup',this.handleFieldChange.bind(this), false);
            })
        });
        this.form.onsubmit = this.reviewValidation.bind(this);
    }
    handleFieldChange(event: MouseEvent ) : void {
        let rules = this.getWatchRules((<ICustomElement>(event.target)).name );
        let field = this.getWatchField((<ICustomElement>(event.target)).name);
        let result : IValidateResult = this.validateField(field[0],rules[0]);
        if(result.status === false){
           this.setFieldError(result)
           return;
        }
        this.clearFieldError(result);
        this.asyncCallBacks = rules[0].asyncValidation;
        this.asyncTotalCount = rules[0].asyncValidation.length;
        this.asyncCount = 0;
        if(this.asyncTotalCount > 0){
            this.callAsyncValidation(field[0].value, field[0]);
        }

    }
    validateField(field: ICustomElement, rules: IFormField) : IValidateResult{
        let parsedRules: IParsedRule;
        let valResult: IValidateResult;
        let result: boolean;
        try {
            rules.validation.forEach((rule, index)=>{
                if( typeof rule === 'function'){
                    let rv: IValidateResult = rule(field);
                    if(!rv.status){
                        throw rv;
                    }
                } else if(typeof rule === 'string'){
                    parsedRules = this.parseRuleParams(rule)
                    if(parsedRules.params === ''){
                        parsedRules.params = field.value;
                    }

                    switch (parsedRules.func) {
                        case 'min':
                        case 'max':
                        case 'required':
                        case 'validEmail':
                        case 'mobileNumber':
                        case 'securePassword':
                            result = this[parsedRules.func](field, parsedRules.params );
                            if( result === false ){
                                valResult = {
                                    status: false,
                                    element: field,
                                    message: rules.errorMessages[index]
                                }
                                throw valResult;
                            }
                            break;
                        case 'matchField':
                            let target = this.getWatchField(parsedRules.params)[0];
                            result = this[parsedRules.func](field, target.value );
                            if( result === false ){
                                valResult = {
                                    status: false,
                                    element: field,
                                    message: rules.errorMessages[index]
                                }
                                throw valResult;
                            }
                            break;
                        case 'range':
                            let limit = parsedRules.params.split(',')
                            result = this[parsedRules.func](field, limit[0], limit[1] );
                            if( result === false ){
                                valResult = {
                                    status: false,
                                    element: field,
                                    message: rules.errorMessages[index]
                                }
                                throw valResult;
                            }
                            break;
                        default:
                            console.log("Unknown Validation....", rule );
                            break;
                    }
                }
            });
        }catch (error) {
            return error;
        }
        return {
            status: true,
            element: field,
            message: ""
        };
    }
    setFieldError(result : IValidateResult) : void {
        if(result.element.type === 'radio'){
            result.element.isValidate = false;
            this.setRadioError(result);
            //result.element.focus();
            return;
        }else if( result.element.type === 'checkbox'){
            result.element.isValidate = false;
            this.setCheckBoxError(result);
            //result.element.focus();
            return;
        }
        if(this.form.querySelector(`#${result.element.name}`)){
            result.element.classList.remove('isValid');
            result.element.classList.add('hasError');
            this.form.querySelector(`#${result.element.name}`).innerHTML =  '&nbsp;&nbsp;' + result.message;
            result.element.isValidate = false;
        }else{
            let errorDiv = document.createElement('DIV');
            errorDiv.className = 'fieldError';
            errorDiv.id = result.element.name;
            errorDiv.innerHTML =  '&nbsp;&nbsp;' + result.message;
            result.element.insertAdjacentElement('afterend', errorDiv);
            //result.element.insertAdjacentElement('beforebegin', errorDiv);
            result.element.classList.remove('isValid');
            result.element.classList.add('hasError');
            result.element.isValidate = false;
        }
        //result.element.focus();
    }
    setCheckBoxError(result : IValidateResult){

        if(this.form.querySelector(`#${result.element.name}`)){
            this.form.querySelector(`#${result.element.name}`).innerHTML =  '&nbsp;&nbsp;' + result.message;
            result.element.isValidate = false;
        } else {
            let errorDiv = document.createElement('DIV');
            errorDiv.className = 'fieldError';
            errorDiv.id = result.element.name;
            errorDiv.innerHTML =  '&nbsp;&nbsp;' + result.message;
            result.element.parentNode.querySelector('label').insertAdjacentElement('afterend', errorDiv);
            result.element.isValidate = false;
        }
    }
    setRadioError(result : IValidateResult){
        let siblings = this.watchFields.filter((watch)=>{
            if(watch.name === result.element.name ){
                return watch;
            }
        });
        if(this.form.querySelector(`#${result.element.name}`)){
            this.form.querySelector(`#${result.element.name}`).innerHTML =  '&nbsp;&nbsp;' + result.message;
            result.element.isValidate = false;
        }else {
            let errorDiv = document.createElement('DIV');
            errorDiv.className = 'fieldError';
            errorDiv.id = result.element.name;
            errorDiv.innerHTML =  '&nbsp;&nbsp;' + result.message;
            siblings[siblings.length-1].parentNode.querySelector('label').insertAdjacentElement('afterend', errorDiv);
            result.element.isValidate = false;
        }
    }
    clearFieldError(result : IValidateResult) : void {
        this.removeErrorElement(result.element.name);
        result.element.isValidate = true;
        result.element.classList.remove('hasError');
        result.element.classList.add('isValid');
    }
    resetField(field: string) : void {
        this.removeErrorElement(field);
        let target : ICustomElement = this.getWatchField(field)[0];
        target.isValidate = true;
        target.classList.remove('hasError');
        target.classList.remove('isValid');
        target.isValidate = false;
        target.value = '';
    }
    removeErrorElement(name: string ) : void {
        if(this.form.querySelector(`#${name}`)){
            this.form.querySelector(`#${name}`).parentNode.removeChild(this.form.querySelector(`#${name}`))
        }
    }
    parseRuleParams( rule ) : IParsedRule{
        let startParse: number;
        let validator: string
        let param: string;

        if(rule.indexOf('(')>0) {
            startParse = rule.indexOf('(');
            validator= rule.substring(0,startParse);
            param= rule.substring(startParse+1,rule.length-1);
        }else {
            validator = rule;
            param = '';
        }
        return {
            func: validator,
            params: param
        }
    }
    getWatchRules(field: string) : IFormField[]{
        return this.watchRules.filter((rule)=>{
            if(rule.name === field){
                return rule;
            }
        });
    }
    getWatchField(field: string) : ICustomElement[]{
        return this.watchFields.filter((watch)=>{
            if(watch.name === field){
                return watch;
            }
        });
    }
    callAsyncValidation(value: any, field: ICustomElement) {
        let request = new XMLHttpRequest();
        let payload = new FormData();
        console.log("Payload Value " , value );
        payload.append(this.asyncCallBacks[this.asyncCount][1], value)
        request.open('POST', `${this.asyncCallBacks[this.asyncCount][0]}`, true);
        let $context = this;
        request.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                $context.validateAsyncCallBack(JSON.parse(this.response), value, field)
            }
        };
        request.onerror = function() {};
        request.send(payload);
    }
    validateAsyncCallBack(response: any, value: any, field: ICustomElement){
        
        if(response.valid){
            console.log("No Errors for ",this.asyncCallBacks[this.asyncCount]);
            this.asyncCount ++;
            if(this.asyncCount < this.asyncTotalCount){
                this.callAsyncValidation(value,field);
            } else {
                console.log("AsyncValidation [EOP]")
            }
        } else {
            console.log("SET ERROR AsyncValidation  ON FIELD ", response, field );
            this.setFieldError({
                status: false,
                element: field,
                message: response.message,
            });
        }
    }

    reviewValidation(evt: Event){
        evt.preventDefault();
        try {
            this.watchFields.forEach((watch)=>{
                if(!watch.isValidate){
                    throw  watch
                }
            });
        } catch (error) {
            console.log("[CATCH ERROR] " , error.type, error.name);
            let event = document.createEvent('HTMLEvents');
            event.initEvent('change', true, false);
            error.dispatchEvent(event);
            this.flash.flashIt({
                type: 'error',
                title: 'Failed Validation',
                message: 'Review form data before submitting, it contains invalid field values'
            })
            return false;
        }
        console.log("Form is validated and ready for Submission...");
        this.form.submit();
    }

    ////////////////////////////////////
    // Built In Validation Functions  //
    ////////////////////////////////////
    min(target:ICustomElement,limit: any) :boolean {

        if(target.type == 'number'){
            return parseInt(target.value) >= limit;
        }else{
            return target.value.length >= limit;
        }
    }
    max(target:ICustomElement,limit: any) :boolean {

        if(target.type == 'number'){
            return parseInt(target.value)<= limit;
        }else{
            return target.value.length <= limit;
        }
    }
    range(target:ICustomElement, min:any, max:any) : boolean {
        // if(/^-?\d+$/.test(target.value)){
        if(target.type == 'number'){
            return (parseInt(target.value) >= min && parseInt(target.value) <= max)

        }else{
            return (target.value.length >= min && target.value.length <= max)
        }
    }
    required( target:ICustomElement) : boolean {
        switch (target.type){
            case 'checkbox':
                return target.checked;
            case 'radio':
                let radioSiblings: ICustomElement[] = this.watchFields.filter((watch)=>{
                    if(watch.name === target.name){
                        return watch;
                    }
                });
                try {
                    radioSiblings.forEach((r)=>{
                        if(r.checked) {
                            throw true
                        }
                    })
                    return false;
                } catch (valid) {
                    radioSiblings.forEach((r)=>{
                        r.isValidate = true
                    })
                    return true
                }
            case 'select-one':
                return parseInt(target.value) !== 0;
            case 'file':
                return target.files.length != 0 ;
            default:
                return !(target.value === '' || target.value === null || parseInt(target.value) === 0);
        }
    }
    validEmail(target:HTMLInputElement)  :boolean {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(target.value.toLowerCase());
    }
    securePassword(target:HTMLInputElement) :boolean {
        const re = /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/;
        return re.test(target.value);
    }
    matchField(target:HTMLInputElement, field2: any) :boolean {

        console.log('matchField ',target.value,field2);

        return target.value === field2
    }
    mobileNumber(target:HTMLInputElement) :boolean {
        let regex = new RegExp("^[0-9]{9}$");
        let number = target.value.replace(/ /g,'');
        let isValid = regex.test(number);
        if(isValid){
            let USNumber2 = number.match(/(\d{3})(\d{3})(\d{3})/);
            target.value = USNumber2[1] + " " + USNumber2[2] + " " + USNumber2[3];
            return true;
        }
        return false;
    }

}