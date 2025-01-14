import type {User} from "../../security/type/User.ts";
import type {Message} from "../../message/type/Message";
import type {Rob} from "../../Rob/type/Rob";

export interface Conversation {
    id: number
    title: string
    description: string
    user: User
    messages: Message[]
    rob : Rob
}