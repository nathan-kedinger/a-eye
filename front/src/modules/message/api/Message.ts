import type {Message} from "../type/Message";
import {ApiResource} from "../../../utils";
export const getMessages = async (robId: number): Promise<Message[]> => {
    return await ApiResource.getCollection(`/messages?rob=${robId}`)

}

export const postMessage = async (message: string): Promise<Message> => {
    return await ApiResource.post('/messages', message)
}