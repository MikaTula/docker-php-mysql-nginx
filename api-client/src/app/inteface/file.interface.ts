export interface IFile {
  id: number;
  userId: number;
  description: string | null;
  originalName: string;
  mimeType: string | null;
  size: number;
  songId: number | null;
  createdAt: Date;
  updatedAt: Date | null;
}
