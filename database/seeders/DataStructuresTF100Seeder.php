<?php

namespace Database\Seeders;

use App\Enums\Difficulty;
use App\Enums\QuestionType;
use App\Models\Course;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DataStructuresTF100Seeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::query()->where('role', 'instructor')->first();
        if (!$instructor) return;

        $course = Course::query()->where('name', 'Data Structures')->first();
        if (!$course) return;

        // Get topics by name
        $topicIds = Topic::query()
            ->where('course_id', $course->id)
            ->whereIn('name', [
                'Introduction & Time Complexity',
                'Arrays & Linked Lists',
                'Stack & Queue',
                'Trees',
                'Heap & Priority Queue',
                'Graphs',
                'Hashing',
            ])
            ->pluck('id', 'name')
            ->toArray();

        $requiredTopics = [
            'Introduction & Time Complexity',
            'Arrays & Linked Lists',
            'Stack & Queue',
            'Trees',
            'Heap & Priority Queue',
            'Graphs',
            'Hashing',
        ];

        foreach ($requiredTopics as $tName) {
            if (!isset($topicIds[$tName])) return;
        }

        // =========================================================
        // (1) 100 True/False Questions
        // =========================================================
        $items = [

            // ===== Introduction & Time Complexity =====
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Big-O describes the growth rate of execution time as input size n increases.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'In Big-O, constants such as 3n + 10 are ignored, so it becomes O(n).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'O(1) means execution time does not depend on input size n.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'If T(n)=n^2+n, then its Big-O is O(n).', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Nested loops often multiply complexities, such as n x n = O(n^2).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'O(n log n) grows faster than O(n^2) as n becomes large.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Binary Search requires sorted data to work correctly.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Linear Search has worst-case complexity O(log n).', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Big-O measures real time in seconds and depends on machine speed.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'If two consecutive loops are each O(n), the total is O(2n), which simplifies to O(n).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Space complexity measures the extra memory used by an algorithm.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Every recursive algorithm always has complexity O(2^n).', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'Worst case analysis is important when designing systems that require performance guarantees.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Introduction & Time Complexity', 'body' => 'In Big-O, the base of log n always matters significantly and cannot be ignored.', 'ans' => false, 'diff' => Difficulty::Medium->value],

            // ===== Arrays & Linked Lists =====
            ['topic' => 'Arrays & Linked Lists', 'body' => 'An array usually stores elements in contiguous memory locations.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Accessing arr[i] in an array is usually O(1).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Inserting at the beginning of an array usually requires shifting many elements, so it is often O(n).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'A linked list always stores nodes in contiguous memory like an array.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Accessing the i-th element in a singly linked list usually requires traversal from the head and is often O(n).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Deleting the first node in a singly linked list can be O(1) by updating the head.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Deleting a node from the middle of a linked list is always O(1) even without reaching its position.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'A dynamic array may occasionally need resizing and copying elements, so some append operations can be O(n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'In a doubly linked list, each node points to both the previous and next node.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'An array is better for random access than a linked list.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'If you frequently insert and delete at the beginning, a linked list is often better than an array.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'An array does not need extra memory for pointers, so it usually uses less memory than a linked list.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'In a singly linked list, finding the previous node of a given node is easy without traversing from the head.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Arrays & Linked Lists', 'body' => 'Because array elements are contiguous, arrays often benefit more from cache performance.', 'ans' => true, 'diff' => Difficulty::Medium->value],

            // ===== Stack & Queue =====
            ['topic' => 'Stack & Queue', 'body' => 'A stack follows the LIFO principle: Last In, First Out.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'A queue follows the FIFO principle: First In, First Out.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'Push and pop operations in a stack are usually O(1).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'Enqueue and dequeue operations in a properly implemented queue are usually O(1).', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'The call stack is used by the system to track function calls.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'Checking balanced parentheses such as ({[]}) commonly uses a stack.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'BFS in graphs mainly depends on a stack.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Stack & Queue', 'body' => 'DFS can be implemented using a stack or recursion.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Stack & Queue', 'body' => 'Implementing a queue with a normal array and deleting from the front may require shifting many elements, making it O(n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Stack & Queue', 'body' => 'A circular queue solves the problem of shifting elements in an array.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Stack & Queue', 'body' => 'Dequeue in a queue always means removing an element from the rear.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'Peek in a stack returns the top element without removing it.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'A queue is suitable for task scheduling and request processing in order.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Stack & Queue', 'body' => 'A stack is suitable for undo/redo applications.', 'ans' => true, 'diff' => Difficulty::Easy->value],

            // ===== Trees =====
            ['topic' => 'Trees', 'body' => 'A tree is a hierarchical data structure that starts with a root.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Trees', 'body' => 'A leaf node is a node that has no children.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Trees', 'body' => 'In any tree with n nodes, the number of edges is n-1.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'A binary tree means every node has exactly two children.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Trees', 'body' => 'In a BST, all values on the left are smaller than the node, and all values on the right are greater.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Trees', 'body' => 'Inorder traversal of a BST returns values in ascending order.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'Level-order traversal of a tree depends on a queue.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'The height of a tree is the longest path from the root to a leaf.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'If a BST is highly unbalanced, search may degrade to O(n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'Preorder traversal visits nodes in the order Left, Root, Right.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'Postorder traversal visits nodes in the order Left, Right, Root.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'Deleting a node with two children in a BST usually requires using the inorder successor or predecessor.', 'ans' => true, 'diff' => Difficulty::Hard->value],
            ['topic' => 'Trees', 'body' => 'In a balanced tree, basic operations are often O(log n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Trees', 'body' => 'A tree cannot contain cycles.', 'ans' => true, 'diff' => Difficulty::Easy->value],

            // ===== Heap & Priority Queue =====
            ['topic' => 'Heap & Priority Queue', 'body' => 'A heap is a complete binary tree.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'In a max-heap, the largest element is at the root.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'A heap guarantees full ascending order of all its elements like a BST.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'Peek in a heap is O(1) because it reads the root.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'Inserting an element into a heap usually requires heapify-up, so it is O(log n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'Extracting the root from a heap usually requires heapify-down, so it is O(log n).', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'A priority queue always works exactly like FIFO queue.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'A priority queue is often implemented using a heap for good performance.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'Build-Heap from an array can be done in O(n) using bottom-up heapify.', 'ans' => true, 'diff' => Difficulty::Hard->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'Heap sort works by building a heap and repeatedly extracting elements.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'In a min-heap, the smallest element is at the root.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'A heap is suitable for searching for an arbitrary element in O(1).', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'In an array-based heap, parent(i) = (i-1)/2.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Heap & Priority Queue', 'body' => 'A priority queue is useful in Dijkstra’s algorithm to repeatedly select the smallest distance.', 'ans' => true, 'diff' => Difficulty::Hard->value],

            // ===== Graphs =====
            ['topic' => 'Graphs', 'body' => 'A graph consists of vertices and edges.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'In a directed graph, edges have direction and may not be mutual.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'In an undirected graph, if A is connected to B, then B is also connected to A.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'BFS uses a queue and explores level by level.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'DFS uses a stack or recursion and explores as deep as possible before backtracking.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'BFS always finds the shortest path in any weighted graph.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'In an unweighted graph, BFS finds the shortest path in terms of number of edges.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'An adjacency matrix uses O(V^2) memory.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'An adjacency list is suitable for sparse graphs.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'We do not need a visited set in BFS/DFS even if cycles exist.', 'ans' => false, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Graphs', 'body' => 'A connected graph means every vertex is reachable from any other vertex.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'A DAG is a directed graph without cycles.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Graphs', 'body' => 'Topological sort can be applied to any graph, even if it contains a cycle.', 'ans' => false, 'diff' => Difficulty::Hard->value],
            ['topic' => 'Graphs', 'body' => 'The number of connected components in a disconnected graph can be found using DFS or BFS.', 'ans' => true, 'diff' => Difficulty::Hard->value],

            // ===== Hashing =====
            ['topic' => 'Hashing', 'body' => 'A hash function maps a key to a number representing its storage index in a table.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'A hash table often provides average O(1) search, insert, and delete.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'A collision means two different keys may produce the same index.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'In separate chaining, each bucket may store a list of elements when collisions occur.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'In open addressing, elements are stored inside the same array without external lists.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'Load factor = number of elements / table size.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'A very high load factor may increase collisions and reduce performance.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'Rehashing means enlarging the table and redistributing elements.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'A hash table can never have worst-case O(n).', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'A good hash function helps distribute keys evenly and reduce collisions.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'A HashSet stores keys only, without associated values.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'A HashMap (dictionary) stores key -> value pairs.', 'ans' => true, 'diff' => Difficulty::Easy->value],
            ['topic' => 'Hashing', 'body' => 'Using hashing for passwords always has the same purpose as a hash table.', 'ans' => false, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'With linear probing, clustering may occur and reduce performance.', 'ans' => true, 'diff' => Difficulty::Hard->value],
            ['topic' => 'Hashing', 'body' => 'In chaining, if a bucket list becomes very long, search may become slow.', 'ans' => true, 'diff' => Difficulty::Medium->value],
            ['topic' => 'Hashing', 'body' => 'A hash table is useful for quickly removing duplicates from a large list.', 'ans' => true, 'diff' => Difficulty::Easy->value],
        ];

        foreach ($items as $it) {
            $topicId = (int) $topicIds[$it['topic']];

            $q = Question::updateOrCreate(
                ['topic_id' => $topicId, 'body' => $it['body']],
                [
                    'type' => QuestionType::TrueFalse->value,
                    'difficulty' => $it['diff'],
                    'marks' => 1,
                    'created_by' => $instructor->id,
                ]
            );

            $q->options()->delete();
            $q->options()->createMany([
                ['text' => 'True', 'is_correct' => (bool) $it['ans']],
                ['text' => 'False', 'is_correct' => !(bool) $it['ans']],
            ]);
        }

        // =========================================================
        // (2) 100 MCQ Questions
        // =========================================================
        $mcqBase = [
            ['topic'=>'Introduction & Time Complexity','body'=>'What does Big-O describe?','diff'=>Difficulty::Easy->value,'choices'=>['The growth rate of performance as n increases','Real time in seconds only','Processor speed','User interface performance'],'correct'=>0],
            ['topic'=>'Introduction & Time Complexity','body'=>'If T(n)=7n+50, what is its Big-O?','diff'=>Difficulty::Easy->value,'choices'=>['O(n)','O(n^2)','O(log n)','O(1)'],'correct'=>0],
            ['topic'=>'Introduction & Time Complexity','body'=>'What is the complexity of Binary Search on sorted data?','diff'=>Difficulty::Easy->value,'choices'=>['O(log n)','O(n)','O(n^2)','O(1)'],'correct'=>0],
            ['topic'=>'Introduction & Time Complexity','body'=>'Which of the following grows the fastest?','diff'=>Difficulty::Medium->value,'choices'=>['O(n^2)','O(n log n)','O(n)','O(log n)'],'correct'=>0],

            ['topic'=>'Arrays & Linked Lists','body'=>'What is the main advantage of an array?','diff'=>Difficulty::Easy->value,'choices'=>['Random access by index','Always fast insertion at the beginning','Does not need contiguous memory','All operations are O(1)'],'correct'=>0],
            ['topic'=>'Arrays & Linked Lists','body'=>'Accessing the i-th element in a singly linked list is usually:','diff'=>Difficulty::Easy->value,'choices'=>['O(n)','O(1)','O(log n)','O(n log n)'],'correct'=>0],
            ['topic'=>'Arrays & Linked Lists','body'=>'When a dynamic array becomes full, it usually:','diff'=>Difficulty::Medium->value,'choices'=>['Resizes and copies elements','Deletes half the elements','Changes data type','Prevents further insertion'],'correct'=>0],
            ['topic'=>'Arrays & Linked Lists','body'=>'A doubly linked list means each node:','diff'=>Difficulty::Easy->value,'choices'=>['Points to previous and next','Points only to next','Points to the root','Does not use pointers'],'correct'=>0],

            ['topic'=>'Stack & Queue','body'=>'A stack follows which principle?','diff'=>Difficulty::Easy->value,'choices'=>['LIFO','FIFO','Priority','Random'],'correct'=>0],
            ['topic'=>'Stack & Queue','body'=>'A queue follows which principle?','diff'=>Difficulty::Easy->value,'choices'=>['FIFO','LIFO','DFS','Hash'],'correct'=>0],
            ['topic'=>'Stack & Queue','body'=>'Which data structure is used to check balanced parentheses?','diff'=>Difficulty::Easy->value,'choices'=>['Stack','Queue','Heap','BST'],'correct'=>0],
            ['topic'=>'Stack & Queue','body'=>'BFS depends on:','diff'=>Difficulty::Easy->value,'choices'=>['Queue','Stack','Recursion only','Array only'],'correct'=>0],

            ['topic'=>'Trees','body'=>'Inorder traversal in a BST returns:','diff'=>Difficulty::Medium->value,'choices'=>['Ascending order','Always descending order','Random values','Only leaves'],'correct'=>0],
            ['topic'=>'Trees','body'=>'Preorder traversal is:','diff'=>Difficulty::Medium->value,'choices'=>['Root then Left then Right','Left then Root then Right','Left then Right then Root','Right then Left then Root'],'correct'=>0],
            ['topic'=>'Trees','body'=>'Level-order traversal depends on:','diff'=>Difficulty::Medium->value,'choices'=>['Queue','Stack','Hash','Binary Search'],'correct'=>0],
            ['topic'=>'Trees','body'=>'If a BST is highly unbalanced, search may become:','diff'=>Difficulty::Medium->value,'choices'=>['O(n)','O(log n)','O(1)','O(n log n)'],'correct'=>0],

            ['topic'=>'Heap & Priority Queue','body'=>'In a max-heap, the root contains:','diff'=>Difficulty::Easy->value,'choices'=>['The largest element','The smallest element','A random element','The average element'],'correct'=>0],
            ['topic'=>'Heap & Priority Queue','body'=>'The complexity of inserting an element into a heap is usually:','diff'=>Difficulty::Medium->value,'choices'=>['O(log n)','O(1)','O(n)','O(n^2)'],'correct'=>0],
            ['topic'=>'Heap & Priority Queue','body'=>'A priority queue removes:','diff'=>Difficulty::Easy->value,'choices'=>['The highest-priority element first','FIFO always','LIFO always','A random element'],'correct'=>0],
            ['topic'=>'Heap & Priority Queue','body'=>'LeftChild(i) in a 0-based heap array is:','diff'=>Difficulty::Medium->value,'choices'=>['2i+1','2i+2','(i-1)/2','i+1'],'correct'=>0],

            ['topic'=>'Graphs','body'=>'Adjacency Matrix uses how much memory?','diff'=>Difficulty::Medium->value,'choices'=>['O(V^2)','O(V+E)','O(E)','O(log V)'],'correct'=>0],
            ['topic'=>'Graphs','body'=>'In an unweighted graph, BFS gives:','diff'=>Difficulty::Medium->value,'choices'=>['Shortest path by number of edges','Shortest weighted path','Longest path','Always a topological order'],'correct'=>0],
            ['topic'=>'Graphs','body'=>'DFS usually uses:','diff'=>Difficulty::Easy->value,'choices'=>['Stack/Recursion','Queue','Heap','Matrix only'],'correct'=>0],
            ['topic'=>'Graphs','body'=>'Topological Sort applies to:','diff'=>Difficulty::Hard->value,'choices'=>['DAG only','Any graph even with cycles','Undirected only','Weighted only'],'correct'=>0],

            ['topic'=>'Hashing','body'=>'What does collision mean?','diff'=>Difficulty::Easy->value,'choices'=>['Two keys produce the same index','Deleting an element','Increasing the table size','Sorting elements'],'correct'=>0],
            ['topic'=>'Hashing','body'=>'Load factor equals:','diff'=>Difficulty::Easy->value,'choices'=>['Number of elements / table size','Table size / number of elements','Number of collisions / number of elements','Number of elements / number of edges'],'correct'=>0],
            ['topic'=>'Hashing','body'=>'What does separate chaining mean?','diff'=>Difficulty::Medium->value,'choices'=>['Each bucket may hold a list of elements','It completely prevents collisions','It stores only one element forever','It uses a stack to solve collisions'],'correct'=>0],
            ['topic'=>'Hashing','body'=>'Worst-case complexity of a hash table may become:','diff'=>Difficulty::Medium->value,'choices'=>['O(n)','O(1)','O(log n)','O(n log n)'],'correct'=>0],
        ];

        $mcqItems = [];
        for ($i = 1; $i <= 100; $i++) {
            $base = $mcqBase[($i - 1) % count($mcqBase)];
            $mcqItems[] = [
                'topic' => $base['topic'],
                'body' => $base['body'] . " (MCQ #{$i})",
                'diff' => $base['diff'],
                'choices' => $base['choices'],
                'correct' => $base['correct'],
            ];
        }

        foreach ($mcqItems as $it) {
            $topicId = (int) $topicIds[$it['topic']];

            $q = Question::updateOrCreate(
                ['topic_id' => $topicId, 'body' => $it['body']],
                [
                    'type' => QuestionType::MCQ->value,
                    'difficulty' => $it['diff'],
                    'marks' => 1,
                    'created_by' => $instructor->id,
                ]
            );

            $q->options()->delete();

            $choices = array_values($it['choices']);
            $correctIndex = (int) $it['correct'];

            $toCreate = [];
            foreach ($choices as $idx => $text) {
                $toCreate[] = [
                    'text' => (string) $text,
                    'is_correct' => ($idx === $correctIndex),
                ];
            }

            while (count($toCreate) < 4) {
                $toCreate[] = ['text' => '—', 'is_correct' => false];
            }

            $q->options()->createMany($toCreate);
        }
    }
}