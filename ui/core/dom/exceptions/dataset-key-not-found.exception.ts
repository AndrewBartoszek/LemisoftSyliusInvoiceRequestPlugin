export class DatasetKeyNotFoundException extends Error {
    constructor() {
        super('Dataset key not found exception.');
    }
}
