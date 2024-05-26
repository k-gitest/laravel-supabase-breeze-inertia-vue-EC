import type { Product } from './product'
import type { Warehouse } from './warehouse'

export type Stock = {
  id: number;
  product_id: number;
  warehouse_id: number;
  quantity: number;
  reserved_quantity: number;
  created_at: string;
  updated_at: string;
  product?: Product;
  warehouse?: Warehouse;
}