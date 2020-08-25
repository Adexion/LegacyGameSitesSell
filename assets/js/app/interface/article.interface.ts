export interface ArticleInterface {
    id: number,
    image: string,
    shortText: string,
    subhead: string,
    text: string,
    title: string,
    createdAt: Date,
    author: {
        username?: string
    },
}
