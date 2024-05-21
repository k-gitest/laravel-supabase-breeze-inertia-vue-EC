import type { Product } from '@/types/product'

export type Favorite = {
  id: number;
  user_id: number;
  product_id: number;
  product: Product;
}