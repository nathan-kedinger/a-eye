import {ApiResource} from "../../../utils";
import type {User} from "../type/User";

export const getAuthenticatedUser = async (): Promise<User> => {
    return await ApiResource.getItem('/me')
}