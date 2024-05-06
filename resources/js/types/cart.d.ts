import type { Product } from './product'

export type Cart = {
  id: number;
  product_id: number;
  user_id: number;
  quantity: number;
  created_at: string;
  updated_at: string;
  product?: Product;
}