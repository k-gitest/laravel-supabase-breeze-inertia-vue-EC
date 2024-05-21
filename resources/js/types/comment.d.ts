import type { Product } from '@/types/product'

export type Comment = {
  id: number;
  user_id: number;
  product_id: number;
  product?: Product;
  title: string;
  content: string;
  created_at: string;
}