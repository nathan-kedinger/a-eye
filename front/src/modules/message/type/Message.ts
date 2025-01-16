import type {User} from "../../security/type/User";
import type {Rob} from "../../Rob/type/Rob.ts";
import type {Conversation} from "../../conversations/type/Conversation.ts";

export interface Message {
    id: number
    '@id': string
    content: string
    sender: User
    timeStamp: string
    readed: boolean
    sentByHuman: boolean
    rob: Rob
    conversation: Conversation
}