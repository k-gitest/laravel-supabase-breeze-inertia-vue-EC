import { Config } from "ziggy-js";
import type { Category } from "./category";
import type { Product } from "./product";

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    subscribed: boolean;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
    flash: {
        success: string;
        error: string;
    }
    category: {
      data: Category[];
    }
    totalPrice: {
        total_price_excluding_tax: number;
        total_price_including_tax: number;
    }
    clientSecret: string;
    filters: {
        q: string;
        category_ids: number[];
        warehouse_check: boolean;
        price_range: string[],
        sort_option: string;
    }
    price_ranges: Record<string, [number, number | null]>;
    //sort_options: Record<string, [number, number | null]>;
};
