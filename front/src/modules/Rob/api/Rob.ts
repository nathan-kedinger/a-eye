import {ApiResource} from "../../../utils/";
import type {Rob} from "../type/Rob";

export const getRob = async (id: number): Promise<Rob> => {
    return await ApiResource.getItem(`/robs/${id}`)
}

export const getRobsCollection = async (): Promise<Rob[]> => {
    return await ApiResource.getCollection('/robs')
}