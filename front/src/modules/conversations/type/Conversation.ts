import type {User} from "../../security/type/User.ts";
import type {Message} from "../../message/type/Message";

export interface Conversation {
    id?: number
    '@id'?: string
    title: string
    description?: string
    lastUpdate?: Date
    user?: User
    messages?: Message[]
    rob?: string
}