import type { Stock } from './stock'

export type Warehouse = {
  id: number;
  name: string;
  location: string;
  created_at: string;
  updated_at: string;
  stock?: Stock[];
}