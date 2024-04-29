export type Contact = {
    id: string;
    name: string;
    email: string;
    message: string;
    created_at: string;
    updated_at: string;
    attachments: {
        id: string;
        key: string;
    }[];
}