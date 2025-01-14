import { defineStore } from 'pinia';
import {ref} from "vue";
import type {Chat} from "../type/Chat";
import {getMessages} from "../../message/api/Message";
import {getAuthenticatedUser} from "../../security/api/Authentication";
import {getConversation} from "../../conversations/api/Conversation"; // Assure-toi que le type Chat est bien défini

export const useChatStore = defineStore('chat',  () => {
    const chats = ref<Chat[]>([]);  // Liste des chats

    const me = ref<any>()
    me.value = getAuthenticatedUser()
    const messages = async (robId: number) => {await getMessages(robId)}
    const conversation = async (robId: number) =>await getConversation(robId)

    const focusedChat = ref<Chat>()
    // Ajouter un chat à la collection
    const addChat = (chat: Chat) => {
        chats.value.push(chat);

    };

    const focusChat = (chat: Chat) => {
        focusedChat.value = chat;
    }

    // Supprimer un chat par ID
    const removeChat = (chatId: number) => {
        chats.value = chats.value.filter(chat => chat.id !== chatId);
    };

    // Vider la collection de chats
    const clearChats = () => {
        chats.value = [];
    };

    // Getter pour obtenir la liste des chats
    const getChats = () => {
        return chats.value;
    };

    // Retourner les propriétés et méthodes
    return {chats, addChat, removeChat, clearChats, getChats};
});
