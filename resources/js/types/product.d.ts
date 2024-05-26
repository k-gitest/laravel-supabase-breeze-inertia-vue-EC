import type { Category } from './category'
import type { Image } from './image'
import type { Favorite } from './favorite'
import type { Comment } from './comment'
import type { Stock } from './stock'

export type Product = {
  id: number;
  name: string;
  description: string;
  price_excluding_tax: number;
  price_including_tax: number;
  category_id: number;
  category?: Category;
  tax_rate: number;
  image?: Image[];
  created_at: string;
  favorite?: Favorite[];
  comment?: Comment[];
  stock?: Stock[];
  stock_sum_quantity?: number;
}