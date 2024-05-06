import type { Product } from './product'

export type Category = {
  id: number;
  name: string;
  description: string;
  created_at: string;
  updated_at: string;
  product?: Product[];
}