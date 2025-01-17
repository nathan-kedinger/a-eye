import type {User} from "../../security/type/User.ts";
import type {Message} from "../../message/type/Message";
import type {Rob} from "../../Rob/type/Rob";

export interface Conversation {
    id?: number
    '@id'?: string
    title: string
    description?: string
    lastUpdate?: Date
    user?: User
    messages?: Message[]
    rob?: Rob
}