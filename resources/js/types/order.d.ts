import type { Product } from "@/types/product"

export type Order = {
  id: number;
  user_id: number;
  total_amount: number;
  payment_intent_id: number;
  status: string;
  currency: string;
  created_at: string;
  updated_at: string;
  order_items?: OrderItem[];
}

export type OrderItem = {
  id: number;  
  order_id: number;
  product_id: number;
  user_id: number;
  quantity: number;
  price_excluding_tax: number;
  price_including_tax: number;
  created_at: string;
  updated_at: string;
  product?: Product;
}