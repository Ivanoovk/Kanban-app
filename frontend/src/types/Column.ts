import type { Task } from "./Task";

// Describes the structure of a column
export interface Column {
  id: number;
  title: string;
  board_id: number;
  order: number;
  tasks: Task[];
}