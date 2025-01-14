import type {User} from "../../security/type/User";

export interface Message {
    id: number
    content: string
    sender: User
    timeStamp: string
    readed: boolean
    sentByHuman: boolean
}