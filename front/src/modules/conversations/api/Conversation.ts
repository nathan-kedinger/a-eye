import {ApiResource} from "../../../utils";
import type {Conversation} from "../type/Conversation";

export const getConversation = async (conversationId: number): Promise<Conversation> => {
    return await ApiResource.getItem(`conversations/${conversationId}`)
}

export const getLatestConversation = async (robId: number): Promise<Conversation> => {
    return await ApiResource.getItem(`conversation/last-modified/${robId}`)
}

export const getConversationsList = async (robId: number): Promise<Conversation[]> => {
    return await ApiResource.getCollection(`conversations?rob.id=${robId}`)
}

export const postConversation = async (conversation: Conversation): Promise<Conversation> => {
    return await ApiResource.post('/conversations', conversation)
}