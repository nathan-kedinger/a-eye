import {ApiResource} from "../../../utils";
import type {Conversation} from "../type/Conversation";

export const getConversation = async (conversationId: number): Promise<Conversation> => {
    return await ApiResource.getItem(`conversations/${conversationId}`)
}

export const getConversationsList = async (robId: number): Promise<Conversation[]> => {
    return await ApiResource.getCollection(`conversations?rob.id=${robId}`)
}