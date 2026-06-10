<?php

namespace Database\Seeders;

use App\Enums\Difficulty;
use App\Enums\QuestionType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['name' => 'Data Structures'],
            ['description' => 'Main course for data structures practice, lessons, and exams.']
        );

        $topics = [
            [
                'name' => 'Arrays',
                'description' => 'Arrays store elements in indexed positions and allow fast access by index.',
                'concepts' => ['Index', 'Element', 'Fixed size', 'Traversal', 'Insertion', 'Deletion', 'Searching', 'Updating'],
                'uses' => ['storing student grades', 'saving product prices', 'working with lists of numbers', 'implementing other data structures'],
                'advantages' => ['fast access using index', 'simple structure', 'easy traversal', 'memory order is clear'],
                'limits' => ['fixed size in many languages', 'slow insertion in the middle', 'slow deletion in the middle', 'same data type is usually required'],
                'easy' => 'Which data structure stores elements using indexes?',
                'medium' => 'What is the time complexity of accessing an element in an array by index?',
                'hard' => 'What is the main disadvantage of inserting an element in the middle of an array?',
                'correct' => ['easy' => 'Array', 'medium' => 'O(1)', 'hard' => 'Elements may need to be shifted'],
                'wrong' => ['Stack', 'Queue', 'Tree'],
            ],
            [
                'name' => 'Linked Lists',
                'description' => 'Linked lists store data in nodes connected by references or pointers.',
                'concepts' => ['Node', 'Data', 'Pointer', 'Head', 'Tail', 'Singly linked list', 'Doubly linked list', 'Traversal'],
                'uses' => ['dynamic memory storage', 'implementing stacks', 'implementing queues', 'frequent insertion and deletion'],
                'advantages' => ['dynamic size', 'easy insertion', 'easy deletion', 'does not require continuous memory'],
                'limits' => ['slow random access', 'extra memory for pointers', 'more complex than arrays', 'traversal is required'],
                'easy' => 'What does each node in a linked list usually contain?',
                'medium' => 'What is an advantage of a linked list over an array?',
                'hard' => 'Why is random access slower in a linked list than in an array?',
                'correct' => ['easy' => 'Data and a reference to another node', 'medium' => 'Dynamic size', 'hard' => 'Nodes must be visited one by one'],
                'wrong' => ['Fixed index only', 'Direct memory indexing', 'Always sorted data'],
            ],
            [
                'name' => 'Stacks',
                'description' => 'Stacks follow the LIFO principle, meaning Last In First Out.',
                'concepts' => ['LIFO', 'Push', 'Pop', 'Peek', 'Top', 'Overflow', 'Underflow', 'Function calls'],
                'uses' => ['undo operations', 'function call management', 'expression evaluation', 'backtracking'],
                'advantages' => ['simple operations', 'useful for reversing data', 'supports recursion', 'fast top access'],
                'limits' => ['access only from the top', 'not suitable for random access', 'can overflow if size is fixed', 'limited operation style'],
                'easy' => 'Which principle does a stack follow?',
                'medium' => 'Which operation adds an element to a stack?',
                'hard' => 'Which problem can be solved using a stack?',
                'correct' => ['easy' => 'LIFO', 'medium' => 'Push', 'hard' => 'Expression evaluation'],
                'wrong' => ['FIFO', 'Enqueue', 'Binary searching only'],
            ],
            [
                'name' => 'Queues',
                'description' => 'Queues follow the FIFO principle, meaning First In First Out.',
                'concepts' => ['FIFO', 'Enqueue', 'Dequeue', 'Front', 'Rear', 'Circular queue', 'Priority queue', 'Waiting line'],
                'uses' => ['printer queues', 'CPU scheduling', 'task processing', 'network packet handling'],
                'advantages' => ['fair ordering', 'simple processing model', 'useful for scheduling', 'matches real waiting lines'],
                'limits' => ['access mainly from front and rear', 'not suitable for random access', 'fixed queues may overflow', 'priority handling needs special queue'],
                'easy' => 'Which principle does a queue follow?',
                'medium' => 'Which operation removes an element from a queue?',
                'hard' => 'Which real-life example best represents a queue?',
                'correct' => ['easy' => 'FIFO', 'medium' => 'Dequeue', 'hard' => 'People waiting in line'],
                'wrong' => ['LIFO', 'Push', 'Random access'],
            ],
            [
                'name' => 'Trees',
                'description' => 'Trees represent hierarchical relationships using nodes and edges.',
                'concepts' => ['Root', 'Parent', 'Child', 'Leaf', 'Height', 'Depth', 'Binary tree', 'Binary search tree'],
                'uses' => ['file systems', 'database indexing', 'decision trees', 'hierarchical data representation'],
                'advantages' => ['represents hierarchy clearly', 'supports efficient searching when balanced', 'useful for sorted data', 'supports traversal methods'],
                'limits' => ['can become unbalanced', 'more complex than linear structures', 'requires pointers or references', 'some operations need recursion'],
                'easy' => 'Which data structure is commonly used to represent hierarchical data?',
                'medium' => 'What is the top node of a tree called?',
                'hard' => 'What happens to search performance when a BST becomes highly unbalanced?',
                'correct' => ['easy' => 'Tree', 'medium' => 'Root', 'hard' => 'It may become O(n)'],
                'wrong' => ['Queue', 'Leaf only', 'It becomes O(1)'],
            ],
            [
                'name' => 'Graphs',
                'description' => 'Graphs represent relationships using vertices and edges.',
                'concepts' => ['Vertex', 'Edge', 'Directed graph', 'Undirected graph', 'Weighted graph', 'Cycle', 'BFS', 'DFS'],
                'uses' => ['maps', 'social networks', 'computer networks', 'route planning'],
                'advantages' => ['models complex relationships', 'supports many algorithms', 'useful for paths and networks', 'flexible structure'],
                'limits' => ['can be complex to implement', 'may need large memory', 'some algorithms are expensive', 'cycles can make traversal harder'],
                'easy' => 'A graph consists mainly of vertices and what?',
                'medium' => 'Which algorithm is commonly used to traverse a graph level by level?',
                'hard' => 'What does a cycle mean in a graph?',
                'correct' => ['easy' => 'Edges', 'medium' => 'BFS', 'hard' => 'A path that starts and ends at the same vertex'],
                'wrong' => ['Indexes', 'Bubble Sort', 'A node with no edges'],
            ],
            [
                'name' => 'Hashing',
                'description' => 'Hashing uses a hash function to store and find data quickly.',
                'concepts' => ['Hash function', 'Key', 'Hash value', 'Hash table', 'Collision', 'Chaining', 'Open addressing', 'Load factor'],
                'uses' => ['fast searching', 'database indexing', 'caching', 'password storage'],
                'advantages' => ['very fast average lookup', 'efficient insertion', 'efficient deletion', 'good for key-value data'],
                'limits' => ['collisions can happen', 'bad hash functions reduce performance', 'ordering is not preserved', 'resizing may be needed'],
                'easy' => 'What is hashing mainly used for?',
                'medium' => 'What is a collision in hashing?',
                'hard' => 'Why is a good hash function important?',
                'correct' => ['easy' => 'Fast data lookup', 'medium' => 'Two keys produce the same hash value', 'hard' => 'It reduces collisions'],
                'wrong' => ['Drawing trees', 'Sorting only', 'It deletes all data'],
            ],
            [
                'name' => 'Heap & Priority Queue',
                'description' => 'Heaps are tree-based structures commonly used to implement priority queues.',
                'concepts' => ['Heap', 'Max heap', 'Min heap', 'Root', 'Priority', 'Insert', 'Remove', 'Heapify'],
                'uses' => ['priority queues', 'CPU scheduling', 'Dijkstra algorithm', 'heap sort'],
                'advantages' => ['efficient priority access', 'fast insert and remove', 'good for scheduling', 'supports heap sort'],
                'limits' => ['not good for searching all values', 'structure is not fully sorted', 'implementation needs heap property', 'random access is not the goal'],
                'easy' => 'A priority queue removes elements based on what?',
                'medium' => 'Which data structure is commonly used to implement a priority queue?',
                'hard' => 'In a max heap, where is the largest element stored?',
                'correct' => ['easy' => 'Priority', 'medium' => 'Heap', 'hard' => 'At the root'],
                'wrong' => ['Random order', 'Stack only', 'At the last leaf'],
            ],
            [
                'name' => 'Sorting',
                'description' => 'Sorting arranges data in a specific order such as ascending or descending.',
                'concepts' => ['Ascending order', 'Descending order', 'Bubble sort', 'Selection sort', 'Insertion sort', 'Merge sort', 'Quick sort', 'Time complexity'],
                'uses' => ['organizing records', 'preparing data for searching', 'ranking results', 'displaying reports'],
                'advantages' => ['makes data easier to read', 'improves search operations', 'helps in analysis', 'important for many algorithms'],
                'limits' => ['some algorithms are slow', 'large data needs efficient methods', 'extra memory may be required', 'wrong algorithm choice affects performance'],
                'easy' => 'What is the purpose of a sorting algorithm?',
                'medium' => 'Which sorting algorithm repeatedly swaps adjacent elements?',
                'hard' => 'What is the average time complexity of quicksort?',
                'correct' => ['easy' => 'Arrange data in a specific order', 'medium' => 'Bubble Sort', 'hard' => 'O(n log n)'],
                'wrong' => ['Delete all data', 'Binary Search', 'O(1)'],
            ],
            [
                'name' => 'Searching',
                'description' => 'Searching finds a required item inside a collection of data.',
                'concepts' => ['Target item', 'Linear search', 'Binary search', 'Sorted data', 'Comparison', 'Search key', 'O(n)', 'O(log n)'],
                'uses' => ['finding student records', 'searching products', 'looking up contacts', 'database queries'],
                'advantages' => ['helps retrieve data', 'simple algorithms are easy to understand', 'binary search is efficient', 'important in real systems'],
                'limits' => ['linear search is slow for large data', 'binary search requires sorted data', 'wrong method wastes time', 'large datasets need indexing'],
                'easy' => 'What is the purpose of a searching algorithm?',
                'medium' => 'Which search algorithm requires sorted data?',
                'hard' => 'What is the time complexity of binary search?',
                'correct' => ['easy' => 'Find a specific item in data', 'medium' => 'Binary Search', 'hard' => 'O(log n)'],
                'wrong' => ['Sort data only', 'Linear Search always', 'O(n²)'],
            ],
        ];

        $instructor = User::where('role', 'instructor')->first();

        foreach ($topics as $t) {
            $topic = Topic::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'name' => $t['name'],
                ],
                [
                    'description' => $t['description'],
                ]
            );

            $lessons = [
                [
                    'title' => 'Detailed Explanation of ' . $t['name'],
                    'content' => $this->buildDetailedLessonContent($t, 'concept'),
                ],
                [
                    'title' => 'Applications and Operations of ' . $t['name'],
                    'content' => $this->buildDetailedLessonContent($t, 'application'),
                ],
            ];

            foreach ($lessons as $lessonData) {
                Lesson::updateOrCreate(
                    [
                        'topic_id' => $topic->id,
                        'title' => $lessonData['title'],
                    ],
                    [
                        'content' => $lessonData['content'],
                    ]
                );
            }

            if (!$instructor) {
                continue;
            }

            $difficultyMap = [
                Difficulty::Easy->value => $t['easy'],
                Difficulty::Medium->value => $t['medium'],
                Difficulty::Hard->value => $t['hard'],
            ];

            foreach ($difficultyMap as $difficulty => $body) {
                $exists = Question::where('topic_id', $topic->id)
                    ->where('difficulty', $difficulty)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $correctText = match ($difficulty) {
                    Difficulty::Easy->value => $t['correct']['easy'],
                    Difficulty::Medium->value => $t['correct']['medium'],
                    Difficulty::Hard->value => $t['correct']['hard'],
                    default => $t['correct']['easy'],
                };

                $wrongOptions = $t['wrong'];

                $question = Question::create([
                    'topic_id' => $topic->id,
                    'body' => $body,
                    'type' => QuestionType::MCQ->value,
                    'difficulty' => $difficulty,
                    'marks' => 1,
                    'created_by' => $instructor->id,
                ]);

                $question->options()->createMany([
                    ['text' => $correctText, 'is_correct' => true],
                    ['text' => $wrongOptions[0] ?? 'Wrong answer 1', 'is_correct' => false],
                    ['text' => $wrongOptions[1] ?? 'Wrong answer 2', 'is_correct' => false],
                    ['text' => $wrongOptions[2] ?? 'Wrong answer 3', 'is_correct' => false],
                ]);
            }
        }
    }

    private function buildDetailedLessonContent(array $topic, string $type): string
    {
        $topicName = $topic['name'];
        $concepts = $topic['concepts'];
        $uses = $topic['uses'];
        $advantages = $topic['advantages'];
        $limits = $topic['limits'];

        $lines = [];

        $lines[] = "1. Topic name: {$topicName}.";
        $lines[] = "2. This lesson explains {$topicName} in a detailed and simple academic way.";
        $lines[] = "3. {$topic['description']}";
        $lines[] = "4. The goal of this lesson is to help the student understand the meaning, use, operations, advantages, and limitations of {$topicName}.";
        $lines[] = "5. {$topicName} is one of the important topics in Data Structures because it helps organize data efficiently.";
        $lines[] = "6. Data structures are used to store, manage, and process data inside computer programs.";
        $lines[] = "7. Choosing the correct data structure improves performance and reduces unnecessary processing.";
        $lines[] = "8. A student should understand when to use {$topicName} and when another structure may be better.";
        $lines[] = "9. The topic is connected to algorithm analysis because every operation has a time cost.";
        $lines[] = "10. Understanding this topic helps in solving programming problems more professionally.";

        $counter = 11;

        foreach ($concepts as $concept) {
            $lines[] = $counter++ . ". Key concept: {$concept} is an important part of {$topicName} and should be understood clearly.";
        }

        foreach ($concepts as $concept) {
            $lines[] = $counter++ . ". In practice, {$concept} helps explain how {$topicName} stores data or performs operations.";
        }

        $lines[] = $counter++ . ". The first step in learning {$topicName} is understanding its structure.";
        $lines[] = $counter++ . ". The structure means how data is arranged and how elements are connected or accessed.";
        $lines[] = $counter++ . ". The second step is understanding the operations that can be applied to the structure.";
        $lines[] = $counter++ . ". Operations usually include insertion, deletion, searching, traversal, and updating when applicable.";
        $lines[] = $counter++ . ". The third step is understanding performance using time complexity.";
        $lines[] = $counter++ . ". Time complexity explains how fast or slow an operation becomes when data size increases.";
        $lines[] = $counter++ . ". Space complexity explains how much memory the structure needs.";
        $lines[] = $counter++ . ". A good programmer studies both time and space before choosing a data structure.";

        foreach ($uses as $use) {
            $lines[] = $counter++ . ". Real use: {$topicName} can be used for {$use}.";
        }

        foreach ($uses as $use) {
            $lines[] = $counter++ . ". This use is important because many software systems need {$use} in daily processing.";
        }

        $lines[] = $counter++ . ". The practical value of {$topicName} appears when the system needs organized data.";
        $lines[] = $counter++ . ". Without a suitable data structure, the program may become slow or difficult to maintain.";
        $lines[] = $counter++ . ". {$topicName} can make some operations easier depending on the problem requirements.";
        $lines[] = $counter++ . ". The developer must compare {$topicName} with other data structures before using it.";
        $lines[] = $counter++ . ". For example, some problems need fast access, while others need fast insertion or deletion.";
        $lines[] = $counter++ . ". The correct choice depends on the most frequent operation in the system.";
        $lines[] = $counter++ . ". If searching is repeated many times, the developer should choose a structure that supports efficient searching.";
        $lines[] = $counter++ . ". If insertion and deletion are repeated many times, the developer should choose a structure that handles them efficiently.";

        foreach ($advantages as $advantage) {
            $lines[] = $counter++ . ". Advantage: {$topicName} provides {$advantage}.";
        }

        foreach ($advantages as $advantage) {
            $lines[] = $counter++ . ". This advantage is useful because {$advantage} can improve the quality of the program.";
        }

        foreach ($limits as $limit) {
            $lines[] = $counter++ . ". Limitation: {$topicName} may have a limitation related to {$limit}.";
        }

        foreach ($limits as $limit) {
            $lines[] = $counter++ . ". The student should remember this limitation because {$limit} can affect performance.";
        }

        $lines[] = $counter++ . ". A common mistake is using {$topicName} without understanding the problem requirements.";
        $lines[] = $counter++ . ". Another mistake is ignoring time complexity and focusing only on writing code.";
        $lines[] = $counter++ . ". Another mistake is assuming that one data structure is always the best choice.";
        $lines[] = $counter++ . ". In real projects, each data structure has strengths and weaknesses.";
        $lines[] = $counter++ . ". Testing is important to make sure that the selected structure works correctly.";
        $lines[] = $counter++ . ". The student should test insertion, deletion, searching, and traversal when possible.";
        $lines[] = $counter++ . ". Debugging becomes easier when the student understands how data moves inside the structure.";
        $lines[] = $counter++ . ". Drawing the structure on paper helps understand it before writing code.";
        $lines[] = $counter++ . ". Using small examples makes learning {$topicName} easier.";
        $lines[] = $counter++ . ". After understanding small examples, the student can apply the concept to larger problems.";

        $lines[] = $counter++ . ". In exams, questions about {$topicName} usually focus on definition, operations, uses, and complexity.";
        $lines[] = $counter++ . ". The student should be able to define {$topicName} in simple words.";
        $lines[] = $counter++ . ". The student should be able to mention at least two real applications.";
        $lines[] = $counter++ . ". The student should be able to compare {$topicName} with another data structure.";
        $lines[] = $counter++ . ". The student should be able to identify the correct operation for a given situation.";
        $lines[] = $counter++ . ". The student should know the main advantage of {$topicName}.";
        $lines[] = $counter++ . ". The student should know the main limitation of {$topicName}.";
        $lines[] = $counter++ . ". The student should understand why performance matters in large systems.";
        $lines[] = $counter++ . ". The student should connect theory with programming practice.";
        $lines[] = $counter++ . ". The student should practice by solving simple questions first, then medium and hard questions.";

        while (count($lines) < 80) {
            $lines[] = $counter++ . ". Additional note: {$topicName} is important because it teaches the student how to think about data organization, problem solving, and efficient program design.";
        }

        if ($type === 'application') {
            $lines[] = $counter++ . ". This application lesson focuses more on how {$topicName} is used in real software systems.";
            $lines[] = $counter++ . ". Real systems do not only need correct answers; they also need efficient and maintainable solutions.";
            $lines[] = $counter++ . ". Therefore, understanding applications of {$topicName} is as important as understanding its definition.";
        } else {
            $lines[] = $counter++ . ". This concept lesson focuses more on the basic idea and theoretical foundation of {$topicName}.";
            $lines[] = $counter++ . ". A strong understanding of the concept makes the practical part easier.";
            $lines[] = $counter++ . ". Therefore, the student should review the definitions and examples carefully.";
        }

        return implode("\n", $lines);
    }
}