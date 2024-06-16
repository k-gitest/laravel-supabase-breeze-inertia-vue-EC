export type PageData<T> = {
  current_page: number;
  first_page_url: string;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  prev_page_url: string | null;
  from: number;
  to: number;
  total: number;
  per_page: number;
  last_page: number;
  path: string;
  data: T[];
  links: PageLink[];
}

export type PageLink = {
  url: string | null;
  label: string;
  active: boolean;
}
